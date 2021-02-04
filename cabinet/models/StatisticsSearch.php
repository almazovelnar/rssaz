<?php

namespace cabinet\models;

use Yii;
use yii\db\Query;
use yii\db\Expression;
use yii\base\Model;
use core\forms\ChartForm;
use InvalidArgumentException;
use core\exceptions\NotFoundException;
use core\services\statistics\TrafficDto;
use core\entities\Statistics\Statistics;
use core\entities\Customer\Website\Website;
use core\services\statistics\TrafficCalculator;
use core\repositories\interfaces\WebsiteRepositoryInterface;

/**
 * Class StatisticsSearch
 * @package cabinet\models
 */
class StatisticsSearch extends Model
{
    const RANGE_TODAY = 'today';
    const RANGE_YESTERDAY = 'yesterday';
    const RANGE_WEEK = 'week';
    const RANGE_MONTH = 'month';

    const DAYS = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

    public int $customerId;
    public ?string $range = null;
    /**
     * @var int|string
     */
    public $websiteId;
    public array $websites;

    private string $start;
    private string $end;

    private WebsiteRepositoryInterface $websiteRepository;
    private TrafficCalculator $trafficCalculator;
    private array $data = [];

    public function __construct(
        WebsiteRepositoryInterface $websiteRepository,
        TrafficCalculator $trafficCalculator,
        int $customerId,
        array $config = []
    )
    {
        parent::__construct($config);

        $this->websiteRepository = $websiteRepository;
        $this->trafficCalculator = $trafficCalculator;
        $this->customerId = $customerId;
        $this->websites = array_map(fn ($website) => $website->id, $websiteRepository->all(['customer' => $customerId, 'status' => Website::STATUS_ACTIVE]));
    }

    public function formName()
    {
        return '';
    }

    public function rules()
    {
        return [
            ['range', 'required'],
            ['websiteId', 'websiteExistValidation'],
            ['range', 'safe'],
        ];
    }

    public function websiteExistValidation($attribute, $params){
        try {
            $this->websiteRepository->get($this->websiteId);
            return true;
        } catch (NotFoundException $e) {
            $this->addError($attribute, 'Uyğun sayt tapılmadı');
            return false;
        }
    }

    public function search($params)
    {
        $query = (new Query())
            ->select(new Expression('SUM(in_views) AS sum_in_views, SUM(in_clicks) AS sum_in_clicks, SUM(out_views) AS sum_out_views, SUM(out_clicks) AS sum_out_clicks'))
            ->from('statistics')
            ->andFilterWhere(['in', 'website_id', $this->websites]);

        $this->load($params);

        if ($this->validate()) {
            $query->andFilterWhere(['website_id' => $this->websiteId]);

            $ranges = explode(' - ', $this->range);
            $this->start = $ranges[0] . ' 00:00:00';
            $this->end = $ranges[1] . ' 23:59:59';
        } else {
            $this->start = date('Y-m-d') . ' 00:00:00';
            $this->end = date('Y-m-d H:i:s');
        }

        $range = strtotime($this->end) - strtotime($this->start);

        if ($range <= 86400) {
            $type = Statistics::TYPE_HOURLY;
        } elseif ($range > 86400 && $range <= 86400 * 31) {
            $type = Statistics::TYPE_DAILY;
        } elseif ($range > 86400 * 31 && $range <= 86400 * 365) {
            $type = Statistics::TYPE_MONTHLY;
        } elseif ($range > 86400 * 365) {
            $type = Statistics::TYPE_YEARLY;
        } else {
            throw new InvalidArgumentException('Invalid range');
        }

        $query->andFilterWhere(['type' => $type])
              ->andWhere(new Expression('created_at BETWEEN :start AND :end', [':start' => $this->start, ':end' => $this->end]));

        $this->data = $query->one();
    }

    public function getInTraffic()
    {
        return (new TrafficDto())
            ->setViews((int) $this->data['sum_in_views'])
            ->setClicks((int) $this->data['sum_in_clicks']);
    }

    public function getOutTraffic()
    {
        return (new TrafficDto())
            ->setViews((int) $this->data['sum_out_views'])
            ->setClicks((int) $this->data['sum_out_clicks']);
    }

    public function getChartConfig()
    {
        $data = [];
        $range = strtotime($this->end) - strtotime($this->start);
        $legends = (new ChartForm())->getLegends(Yii::$app->request);
        if (!$range > 0) return $data;

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

        $statistics = (new Query())
            ->select(new Expression('SUM(in_views) AS sum_in_views, SUM(in_clicks) AS sum_in_clicks, 
                SUM(out_views) AS sum_out_views, SUM(out_clicks) AS sum_out_clicks, created_at'))
            ->from('statistics')
            ->andFilterWhere(['in', 'website_id', $this->websites])
            ->andWhere(['type' => $type])
            ->andWhere(new Expression('created_at BETWEEN :start AND :end', [':start' => $this->start, ':end' => $this->end]))
            ->andFilterWhere(['website_id' => $this->websiteId])
            ->groupBy(new Expression("DATE_FORMAT(created_at, '%" . $format . "')"))
            ->orderBy('created_at')
            ->all();

        if (empty($statistics)) return $data;

        $out = [
            'label' => "Out Traffic (CTR)",
            'backgroundColor' => "rgba(236,13,65,0.9)",
            'borderColor' => "rgba(236,13,65,1)",
            'pointBackgroundColor' => "rgba(236,13,65,1)",
            'pointBorderColor' => "#fff",
            'pointHoverBackgroundColor' => "#fff",
            'pointHoverBorderColor' => "rgba(179,181,198,1)",
            'hidden' => true,
        ];
        $in = [
            'label' => "In Traffic (CTR)",
            'backgroundColor' => "rgba(0,186,108,0.9)",
            'borderColor' => "rgba(0,186,108,1)",
            'pointBackgroundColor' => "rgba(255,99,132,1)",
            'pointBorderColor' => "#fff",
            'pointHoverBackgroundColor' => "#fff",
            'pointHoverBorderColor' => "rgba(255,99,132,1)",
            'hidden' => true,
        ];
        $outClicks = [
            'label' => "Out Clicks",
            'backgroundColor' => "rgba(255,198,53,0.9)",
            'borderColor' => "rgba(255,198,53,1)",
            'pointBackgroundColor' => "rgba(255,198,53,1)",
            'pointBorderColor' => "#fff",
            'pointHoverBackgroundColor' => "#fff",
            'pointHoverBorderColor' => "rgba(2,0,163,1)",
            'hidden' => false,
        ];
        $inClicks = [
            'label' => "In Clicks",
            'backgroundColor' => "rgba(0,183,219,0.9)",
            'borderColor' => "rgba(0,183,219,1)",
            'pointBackgroundColor' => "rgba(0,183,219,1)",
            'pointBorderColor' => "#fff",
            'pointHoverBackgroundColor' => "#fff",
            'pointHoverBorderColor' => "rgba(2,0,163,1)",
            'hidden' => false,
        ];
        $outViews = [
            'label' => "Out Views",
            'backgroundColor' => "rgba(102, 58, 182,0.9)",
            'borderColor' => "rgba(102, 58, 182,1)",
            'pointBackgroundColor' => "rgba(102, 58, 182,1)",
            'pointBorderColor' => "#fff",
            'pointHoverBackgroundColor' => "#fff",
            'pointHoverBorderColor' => "rgba(187,29,49,1)",
            'hidden' => true,
        ];
        $inViews = [
            'label' => "In Views",
            'pointBorderColor' => "#fff",
            'pointHoverBackgroundColor' => "#fff",
            'pointHoverBorderColor' => "rgba(187,29,49,1)",
            'hidden' => true,
        ];

        foreach ($statistics as $statistic) {
            $data['labels'][] = self::formatLabel($range, $statistic['created_at'], $type);
            $in['data'][] = $this->trafficCalculator->calculateCtr((new TrafficDto())
                ->setViews((int) $statistic['sum_in_views'])
                ->setClicks((int) $statistic['sum_in_clicks'])
            );
            $out['data'][] = $this->trafficCalculator->calculateCtr((new TrafficDto())
                ->setViews((int) $statistic['sum_out_views'])
                ->setClicks((int) $statistic['sum_out_clicks'])
            );
            $inClicks['data'][] = $statistic['sum_in_clicks'];
            $outClicks['data'][] = $statistic['sum_out_clicks'];
            $inViews['data'][] = $statistic['sum_in_views'];
            $outViews['data'][] = $statistic['sum_out_views'];
        }

        $datas = [$in, $out, $inClicks, $outClicks, $inViews, $outViews];
        $data['datasets'] = $datas;

        if (!empty($legends)){
            $data['datasets'] = [];
            foreach ($datas as $key =>  $dataset){
                $dataset['hidden'] = $legends[$key] == 'not' ? $dataset['hidden'] : ($legends[$key]=='true' ? true : null);
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
}
