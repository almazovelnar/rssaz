<?php

namespace backend\models;

use Yii;
use yii\db\Query;
use yii\base\Model;
use yii\db\Expression;
use core\forms\ChartForm;
use InvalidArgumentException;
use core\exceptions\NotFoundException;
use core\services\statistics\TrafficDto;
use core\entities\Statistics\Statistics;
use core\entities\Customer\Website\Website;
use core\services\statistics\TrafficCalculator;
use core\repositories\interfaces\WebsiteRepositoryInterface;

/**
 * Class SiteSearch
 * @package backend\models
 */
class SiteSearch extends Model
{
    private const RANGE_TODAY = 'today';
    private const RANGE_YESTERDAY = 'yesterday';
    private const RANGE_WEEK = 'week';
    private const RANGE_MONTH = 'month';

    private const DAYS = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

    public ?string $range = null;
    public ?string $websiteId = null;
    public ?string $algorithm = null;
    /**
     * @var array|string
     */
    private $websites;
    private ?string $start = null;
    private ?string $end = null;
    private ?array $data = [];
    private array $algorithmList;

    private WebsiteRepositoryInterface $websiteRepository;
    private TrafficCalculator $trafficCalculator;

    public function __construct(
        WebsiteRepositoryInterface $websiteRepository,
        TrafficCalculator $trafficCalculator,
        array $config = []
    )
    {
        parent::__construct($config);

        $websiteList = $websiteRepository->all(['status' => Website::STATUS_ACTIVE]);

        $this->websiteRepository = $websiteRepository;
        $this->trafficCalculator = $trafficCalculator;

        $this->websites = array_map(fn($website) => $website->id, $websiteList);
        $this->range = date('Y-m-d') . ' - ' . date('Y-m-d');
        $this->algorithmList = $this->setAlgorithmList($websiteList);
    }

    public function formName(): string
    {
        return '';
    }

    public function rules(): array
    {
        return [
            ['range', 'required'],
            ['websiteId', 'websiteExistsRule'],
            [['range', 'algorithm'], 'safe'],
        ];
    }

    public function websiteExistsRule($attribute, $params): bool
    {
        try {
            $this->websiteRepository->get($this->websiteId);
            return true;
        } catch (NotFoundException $e) {
            return false;
        }
    }

    public function search(array $params): void
    {
        $query = (new Query())
            ->select(new Expression(
                'SUM(in_views) AS sum_in_views, 
              SUM(in_clicks) AS sum_in_clicks, 
              SUM(out_views) AS sum_out_views, 
              SUM(out_clicks) AS sum_out_clicks,
              referrer_type'
            ))
            ->from('statistics')
            ->groupBy('referrer_type');

        $this->load($params);

        if ($this->validate()) {
            if ($this->websiteId) $this->websites = $this->websiteId;
            $query->andFilterWhere(['website_id' => $this->websites]);

            if ($this->algorithm) $query->andFilterWhere(['algorithm' => $this->algorithm]);

            $ranges = explode(' - ', $this->range);
            $this->start = $ranges[0] . ' 00:00:00';
            $this->end = $ranges[1] . ' 23:59:59';
        } else {
            $this->start = date('Y-m-d') . ' 00:00:00';
            $this->end = date('Y-m-d H:i:s');
        }

        $rangeDiff = strtotime($this->end) - strtotime($this->start);

        if ($rangeDiff <= 86400) {
            $type = Statistics::TYPE_HOURLY;
        } elseif ($rangeDiff > 86400 && $rangeDiff <= 86400 * 31) {
            $type = Statistics::TYPE_DAILY;
        } elseif ($rangeDiff > 86400 * 31 && $rangeDiff <= 86400 * 365) {
            $type = Statistics::TYPE_MONTHLY;
        } elseif ($rangeDiff > 86400 * 365) {
            $type = Statistics::TYPE_YEARLY;
        } else {
            throw new InvalidArgumentException('Invalid range !');
        }

        $query->andFilterWhere(['type' => $type])
            ->andWhere(new Expression('created_at BETWEEN :start AND :end', [':start' => $this->start, ':end' => $this->end]));

        $this->data = $query->all();
    }

    public function getInTraffic(): array
    {
        $inTraffic = [];

        $viewsTotal = 0;
        $clicksTotal = 0;
        foreach ($this->data as $data) {
            $inTraffic['referrers'][ucfirst($data['referrer_type'])] = ['in_views' => $data['sum_in_views'], 'in_clicks' => $data['sum_in_clicks']];
            $viewsTotal += $data['sum_in_views'];
            $clicksTotal += $data['sum_in_clicks'];
        }

        $inTraffic['ctr'] = $this->trafficCalculator->calculateCtr((new TrafficDto())
            ->setViews((int)$viewsTotal)
            ->setClicks((int)$clicksTotal));
        $inTraffic['views'] = $viewsTotal;
        $inTraffic['clicks'] = $clicksTotal;

        return $inTraffic;
    }

    public function getOutTraffic(): array
    {
        $outTraffic = [];
        $viewsTotal = 0;
        $clicksTotal = 0;

        foreach ($this->data as $data) {
            $viewsTotal += $data['sum_out_views'];
            $clicksTotal += $data['sum_out_clicks'];
        }

        $outTraffic['ctr'] = $this->trafficCalculator->calculateCtr((new TrafficDto())
            ->setViews((int)$viewsTotal)
            ->setClicks((int)$clicksTotal));;
        $outTraffic['views'] = $viewsTotal;
        $outTraffic['clicks'] = $clicksTotal;

        return $outTraffic;
    }

    public function setAlgorithmList(array $websiteList): array
    {
        $out = [];

        if ($websiteId = (int)Yii::$app->request->get('websiteId', null))
            $websiteList = array_filter($websiteList, fn($website) => $website->id == $websiteId);

        foreach ($websiteList as $website) {
            $algorithms = array_column($website->algorithms, 'algorithm') ?: ['default'];
            $out[$website->name] = array_combine($algorithms, $algorithms);
        }

        return $out;
    }

    public function getAlgorithmList(): array
    {
        return $this->algorithmList;
    }

    public function getChartConfig()
    {
        $data = [];
        $algorithmInBanners = [];
        $algorithmOutClicks = [];

        $range = strtotime($this->end) - strtotime($this->start);
        $legends = (new ChartForm())->getLegends(Yii::$app->request);

        if ($range < 0) return $data;

        switch ($range) {
            case $range <= 86400:
                $type = Statistics::TYPE_HOURLY;
                $format = 'H';
                break;
            case $range > 86400 && $range <= 86400 * 31:
                $type = Statistics::TYPE_DAILY;
                $format = 'd';
                break;
            case $range > 86400 * 31 && $range <= 86400 * 365:
                $type = Statistics::TYPE_MONTHLY;
                $format = 'm';
                break;
            case $range > 86400 * 365:
                $type = Statistics::TYPE_YEARLY;
                $format = 'Y';
                break;
            default:
                throw new InvalidArgumentException('Invalid range');
        }

        $query = (new Query())
            ->select(new Expression('
                SUM(in_views) AS sum_in_views, 
                SUM(in_clicks) AS sum_in_clicks, 
                SUM(out_views) AS sum_out_views, 
                SUM(out_clicks) AS sum_out_clicks, 
                SUM(case when referrer_type = "banners" then in_clicks else 0 end) as bannersClicksCount,
                SUM(case when referrer_type = "redirect" then in_clicks else 0 end) as landingClicksCount,
                SUM(case when referrer_type = "site" then in_clicks else 0 end) as siteClicksCount,
                algorithm,
                created_at'
            ))
            ->from('statistics')
            ->andWhere(['type' => $type])
            ->andWhere(new Expression('created_at BETWEEN :start AND :end', [':start' => $this->start, ':end' => $this->end]))
            ->andFilterWhere(['website_id' => $this->websites])
            ->groupBy(new Expression("DATE_FORMAT(created_at, '%" . $format . "')"))
            ->orderBy('created_at');

        if ($this->algorithm) $query->andFilterWhere(['algorithm' => $this->algorithm]);

        if ($this->checkWebsiteAlgorithms()) {
            $i = 1;
            $k = 50;
            foreach ($this->checkWebsiteAlgorithms() as $algorithm) {
                $algorithmInBanners[] = [
                    'label' => "IN Banners (". $algorithm . ")",
                    'backgroundColor' => "rgba(0,0,0,0)",
                    'borderColor' => "rgba(1{$i},{$i},{$i},{$i})",
                    'pointBackgroundColor' => "rgba(1{$i},{$i},{$i},{$i})",
                    'pointBorderColor' => "#fff",
                    'pointHoverBackgroundColor' => "#fff",
                    'pointHoverBorderColor' => "rgba(179,181,198,1)",
                    'hidden' => false,
                ];

                $algorithmOutClicks[] = [
                    'label' => "Out Clicks (". $algorithm . ")",
                    'backgroundColor' => "rgba(0,0,0,0)",
                    'borderColor' => "rgba({$k},{$k},1{$k},2{$k})",
                    'pointBackgroundColor' => "rgba({$k},{$k},1{$k},2{$k})",
                    'pointBorderColor' => "#fff",
                    'pointHoverBackgroundColor' => "#fff",
                    'pointHoverBorderColor' => "rgba(179,181,198,1)",
                    'hidden' => false,
                ];

                $query->addSelect("SUM(case when referrer_type = 'banners' and algorithm = '{$algorithm}' then in_clicks else 0 end) as {$algorithm}_bannersClicksCount");
                $query->addSelect("SUM(case when algorithm = '{$algorithm}' then out_clicks else 0 end) as {$algorithm}_outClicksCount");
                $i = $i + 20;
                $k = $k + 50;
            }
        }

        $statistics = $query->all();

        if (empty($statistics)) return $data;

        $inBanners = [
            'label' => "IN Banners" . ($this->algorithm ? " ({$this->algorithm})" : ""),
            'backgroundColor' => "rgba(0,0,0,0)",
            'borderColor' => "rgba(236,13,65,1)",
            'pointBackgroundColor' => "rgba(236,13,65,1)",
            'pointBorderColor' => "#fff",
            'pointHoverBackgroundColor' => "#fff",
            'pointHoverBorderColor' => "rgba(179,181,198,1)",
            'hidden' => false,
        ];
        $inLanding = [
            'label' => "IN Landing",
            'backgroundColor' => "rgba(0,0,0,0)",
            'borderColor' => "rgba(0,186,108,1)",
            'pointBackgroundColor' => "rgba(0,186,108,1)",
            'pointBorderColor' => "#fff",
            'pointHoverBackgroundColor' => "#fff",
            'pointHoverBorderColor' => "rgba(255,99,132,1)",
            'hidden' => false,
        ];
        $inSite = [
            'label' => "IN Site",
            'backgroundColor' => "rgba(0,0,0,0)",
            'borderColor' => "rgba(252, 3, 206,1)",
            'pointBackgroundColor' => "rgba(255,99,132,1)",
            'pointBorderColor' => "#fff",
            'pointHoverBackgroundColor' => "#fff",
            'pointHoverBorderColor' => "rgba(255,99,132,1)",
            'hidden' => false,
        ];
        $outClicks = [
            'label' => "Out Clicks" . ($this->algorithm ? " ({$this->algorithm})" : ""),
            'backgroundColor' => "rgba(0,0,0,0)",
            'borderColor' => "rgba(255,198,53,1)",
            'pointBackgroundColor' => "rgba(255,198,53,1)",
            'pointBorderColor' => "#fff",
            'pointHoverBackgroundColor' => "#fff",
            'pointHoverBorderColor' => "rgba(2,0,163,1)",
            'hidden' => false,
        ];
        $inClicks = [
            'label' => "IN Clicks (all)",
            'backgroundColor' => "rgba(0,0,0,0)",
            'borderColor' => "rgba(0,183,219,1)",
            'pointBackgroundColor' => "rgba(0,183,219,1)",
            'pointBorderColor' => "#fff",
            'pointHoverBackgroundColor' => "#fff",
            'pointHoverBorderColor' => "rgba(2,0,163,1)",
            'hidden' => false,
        ];
        $outViews = [
            'label' => "Out Views",
            'backgroundColor' => "rgba(0,0,0,0)",
            'borderColor' => "rgba(102, 58, 182,1)",
            'pointBackgroundColor' => "rgba(102, 58, 182,1)",
            'pointBorderColor' => "#fff",
            'pointHoverBackgroundColor' => "#fff",
            'pointHoverBorderColor' => "rgba(187,29,49,1)",
            'hidden' => false,
        ];
        $inViews = [
            'label' => "In Views",
            'backgroundColor' => "rgba(0,0,0,0)",
            'borderColor' => "rgba(105, 105, 105,1)",
            'pointBackgroundColor' => "rgba(105, 105, 105,1)",
            'pointBorderColor' => "#fff",
            'pointHoverBackgroundColor' => "#fff",
            'pointHoverBorderColor' => "rgba(187,29,49,1)",
            'hidden' => false,
        ];

        foreach ($statistics as $statistic) {
            $data['labels'][] = self::formatLabel($range, $statistic['created_at'], $type);
            $inClicks['data'][] = $statistic['sum_in_clicks'];
            $outClicks['data'][] = $statistic['sum_out_clicks'];
            $inViews['data'][] = $statistic['sum_in_views'];
            $outViews['data'][] = $statistic['sum_out_views'];
            $inBanners['data'][] = $statistic['bannersClicksCount'];
            $inLanding['data'][] = $statistic['landingClicksCount'];
            $inSite['data'][] = $statistic['siteClicksCount'];

            if ($this->checkWebsiteAlgorithms()) {
                $i = 0;
                foreach ($this->checkWebsiteAlgorithms() as $algorithm) {
                    $algorithmInBanners[$i]['data'][] = $statistic["{$algorithm}_bannersClicksCount"];
                    $algorithmOutClicks[$i]['data'][] = $statistic["{$algorithm}_outClicksCount"];
                    $i++;
                }
            }
        }

        $inClicks['label'] = $inClicks['label'] . ' - ' . array_sum($inClicks['data']) . ' clicks';
        $outClicks['label'] = $outClicks['label'] . ' - ' . array_sum($outClicks['data']) . ' clicks';
        $inViews['label'] = $inViews['label'] . ' - ' . array_sum($inViews['data']) . ' views';
        $outViews['label'] = $outViews['label'] . ' - ' . array_sum($outViews['data']) . ' views';
        $inBanners['label'] = $inBanners['label'] . ' - ' . array_sum($inBanners['data']) . ' clicks';
        $inLanding['label'] = $inLanding['label'] . ' - ' . array_sum($inLanding['data']) . ' clicks';
        $inSite['label'] = $inSite['label'] . ' - ' . array_sum($inSite['data']) . ' clicks';

        if ($this->checkWebsiteAlgorithms()) {
            $i = 0;
            foreach ($algorithmInBanners as $algorithm) {
                $algorithmInBanners[$i]['label'] = $algorithm['label'] . ' - ' . array_sum($algorithm['data']) . ' clicks';
                $i++;
            }
            $i = 0;
            foreach ($algorithmOutClicks as $algorithm) {
                $algorithmOutClicks[$i]['label'] = $algorithm['label'] . ' - ' . array_sum($algorithm['data']) . ' clicks';
                $i++;
            }
        }


        $datas = array_merge($algorithmInBanners,[$inBanners, $inLanding, $inSite, $inClicks], $algorithmOutClicks, [$outClicks, $inViews, $outViews]);

        $data['datasets'] = $datas;

        if (!empty($legends) && (count($legends) == count($datas))) {
            $data['datasets'] = [];
            foreach ($datas as $key => $dataset) {
                $dataset['hidden'] = $legends[$key] == 'not' ? $dataset['hidden'] : ($legends[$key] == 'true' ? true : null);
                $data['datasets'][] = $dataset;
            }
        }
        return $data;
    }

    private static function formatLabel($range, $date, $type)
    {
        switch ($type) {
            case Statistics::TYPE_HOURLY:
                return date('H', strtotime($date)) . ':00';
            case Statistics::TYPE_DAILY:
                if ($range >= 86400 * 7) {
                    return date('d', strtotime($date));
                }
                return date('l', strtotime($date));
            case Statistics::TYPE_MONTHLY:
                return date('F', strtotime($date));
            case Statistics::TYPE_YEARLY:
                return date('Y', strtotime($date));
            default:
                return $date;
        }
    }

    private function checkWebsiteAlgorithms()
    {
        if ($this->websiteId && !$this->algorithm) {
            $algorithms = $this->getAlgorithmList();
            $algorithms = reset($algorithms);

            if (count($algorithms) > 1) {
                return $algorithms;
            }
        }

        return false;
    }
}