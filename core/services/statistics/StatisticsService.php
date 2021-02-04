<?php

namespace core\services\statistics;

use Yii;
use yii\db\Exception;
use yii\helpers\Inflector;
use InvalidArgumentException;
use yii\caching\CacheInterface;
use core\entities\Statistics\Statistics;
use core\entities\Customer\Website\Website;
use yii\db\Connection as MysqlConnection;
use core\repositories\StatisticsRepository;
use kak\clickhouse\Connection as ClickHouseConnection;
use core\repositories\interfaces\WebsiteRepositoryInterface;

/**
 * Class StatisticsService
 * @package core\services\statistics
 */
class StatisticsService
{
    private const INCOMING_TRAFFIC = 'in';
    private const OUTGOING_TRAFFIC = 'out';

    private MysqlConnection $db;
    private ClickHouseConnection $clickHouse;
    private StatisticsRepository $statisticsRepository;
    private WebsiteRepositoryInterface $websiteRepository;
    private CacheInterface $cache;

    public function __construct(
        StatisticsRepository $statisticsRepository,
        WebsiteRepositoryInterface $websiteRepository,
        CacheInterface $cache
    )
    {
        $this->db = Yii::$app->db;
        $this->clickHouse = Yii::$app->clickhouse;
        $this->cache = $cache;
        $this->statisticsRepository = $statisticsRepository;
        $this->websiteRepository = $websiteRepository;
    }

    /**
     * @throws Exception
     */
    public function count()
    {
        foreach ($this->websiteRepository->all(['status' => Website::STATUS_ACTIVE]) as $website) {
            foreach ($this->countTraffic($website) as $referrer => $algorithms) {
                foreach ($algorithms as $algorithm => $traffic) {
                    $this->statisticsRepository->save(Statistics::create(
                        $website->getId(),
                        $traffic['in_views'],
                        $traffic['in_clicks'],
                        $traffic['out_views'],
                        $traffic['out_clicks'],
                        $algorithm,
                        Statistics::TYPE_EVERY_TEN_MINUTES,
                        $referrer
                    ));
                }
            }
            // Calculating website rate every 10 minute.
            $this->calculateRate($website);
        }
        $this->db->createCommand('TRUNCATE TABLE `raw_statistics`')->execute();
    }

    public function summary(string $type)
    {
        $typeMapping = [
            Statistics::TYPE_HOURLY => ['from' => Statistics::TYPE_EVERY_TEN_MINUTES, 'func' => 'HOUR'],
            Statistics::TYPE_DAILY => ['from' => Statistics::TYPE_HOURLY, 'func' => 'DAY'],
            Statistics::TYPE_MONTHLY => ['from' => Statistics::TYPE_DAILY, 'func' => 'MONTH'],
            Statistics::TYPE_YEARLY => ['from' => Statistics::TYPE_MONTHLY, 'func' => 'YEAR'],
        ];

        if (!array_key_exists($type, $typeMapping))
            throw new InvalidArgumentException('Undefined statistics summary type: ' . $type);

        $typeData = $typeMapping[$type];

        $query = $this->db->createCommand("
            SELECT `website_id`, `algorithm`, `referrer_type`,
            SUM(in_views) AS `in_views_count`, 
            SUM(in_clicks) AS `in_clicks_count`, 
            SUM(out_views) AS `out_views_count`, 
            SUM(out_clicks) AS `out_clicks_count` FROM statistics 
            WHERE `type` = :type GROUP BY `website_id`, `algorithm`, `referrer_type`, :func
        ", [':type' => $typeData['from'], ':func' => ($typeData['func'] . '(created_at)')])->query();

        while ($row = $query->read()) {
            $this->statisticsRepository->save(Statistics::create(
                $row['website_id'],
                $row['in_views_count'],
                $row['in_clicks_count'],
                $row['out_views_count'],
                $row['out_clicks_count'],
                $row['algorithm'],
                $type,
                $row['referrer_type']
            ));
        }

        $deleteQuery = 'DELETE FROM `statistics` WHERE type = :type';
        if ($type === Statistics::TYPE_DAILY)
            $deleteQuery .= " AND created_at <= '" . date('Y-m-d', time() - 90000) . ' 23:59:59' . "'";
        $this->db->createCommand($deleteQuery, [':type' => $typeData['from']])->execute();
    }

    private function countTraffic(Website $website): array
    {
        $trafficModeMapping = [
            self::INCOMING_TRAFFIC => 'recipient_website_id',
            self::OUTGOING_TRAFFIC => 'source_website_id',
        ];

        $traffic = [];
        foreach ($trafficModeMapping as $trafficMode => $column) {
            $rawStatistics = $this->db
                ->createCommand("
                    SELECT `type`, COUNT(`id`) `count`, `algorithm`, `referrer_type` FROM `raw_statistics`
                    WHERE `{$column}` = :website_id GROUP BY `type`, `algorithm`, `referrer_type`
                ", [':website_id' => $website->getId()])
                ->queryAll();

            foreach ($rawStatistics as $raw) {
                $traffic[$raw['referrer_type']][$raw['algorithm']] ??= ['in_views' => 0, 'in_clicks' => 0, 'out_views' => 0, 'out_clicks' => 0]; // setting default values.
                $traffic[$raw['referrer_type']][$raw['algorithm']][$trafficMode . '_' . Inflector::pluralize($raw['type'])] = (int) $raw['count'];
            }
        }

        return $traffic;
    }

    private function calculateRate(Website $website): void
    {
        $rateStatistic = $this->db
            ->createCommand('
                SELECT SUM(in_clicks) AS `in`, SUM(out_clicks) AS `out` FROM statistics
                WHERE (DATE(created_at) = CURDATE()) AND (website_id = :website) AND (`type` = :type)
            ', [':website' => $website->getId(), ':type' => 'hourly'])
            ->queryOne();

        $rate = ($rateStatistic['in'] > 0)
            ? ($rateStatistic['out'] / $rateStatistic['in'])
            : $rateStatistic['out'];

        $rateActual = round($rate, 3);
        $this->rememberStats($website, $rateStatistic, $rateActual);
        $this->websiteRepository->update($website->getId(), ['rate_actual' => $rateActual]);
    }

    private function rememberStats(Website $website, array $rateStatistic, float $rateActual): void
    {
        $stats = $this->cache->getOrSet('website_stats', fn() => collect());
        $stats->put($website->getId(), [
            'in_clicks'   => (int) $rateStatistic['in'],
            'out_clicks'  => (int) $rateStatistic['out'],
            'rate_actual' => $rateActual,
            'rated_at'    => date('Y-m-d H:i:s'),
        ]);
        $this->cache->set('website_stats', $stats);
    }
}
