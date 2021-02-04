<?php

namespace backend\modules\statistics\models;

use Yii;
use yii\db\Query;
use yii\base\Model;
use yii\web\Cookie;
use yii\db\Expression;
use core\entities\Parse\Parse;
use yii\data\ActiveDataProvider;
use core\entities\Customer\Website\{Website, Rss};
use core\repositories\interfaces\{WebsiteRepositoryInterface, RssRepositoryInterface};

/**
 * Class StatisticsSearch
 * @package backend\modules\statistics\models
 */
class StatisticsSearch extends Model
{
    public ?string $date = null;
    public ?string $website = null;
    public ?string $status = null;

    private WebsiteRepositoryInterface $websiteRepository;
    private RssRepositoryInterface $rssRepository;
    private ?array $websites;
    private ?array $rss;

    public function __construct(
        WebsiteRepositoryInterface $websiteRepository,
        RssRepositoryInterface $rssRepository,
        $config = []
    )
    {
        parent::__construct($config);

        $this->rssRepository = $rssRepository;
        $this->websiteRepository = $websiteRepository;
        $this->date = $this->getRememberedParams('date');
        $this->websites = $websiteRepository->all(['status' => Website::STATUS_ACTIVE, 'indexing' => 'id']);
        $this->rss = $rssRepository->all(['indexing' => 'id']);
    }

    public function formName(): string
    {
        return '';
    }

    public function rules(): array
    {
        return [
            ['website', 'integer'],
            ['date', 'date', 'format' => 'php: Y-m-d'],
        ];
    }

    public function search(array $params = [])
    {
        $query = Parse::find()
            ->select("rss_id, website_id, status")
            ->addSelect("sum(case when status = 0 then 1 else 0 end) as successCount")
            ->addSelect("sum(case when status = 1 then 1 else 0 end) as warningCount")
            ->addSelect("sum(case when status = 2 then 1 else 0 end) as dangerCount")
            ->addSelect("sum(case when status = 3 then 1 else 0 end) as fatalCount")
            ->andWhere(['in', 'website_id', array_keys($this->websites)])
            ->groupBy(['website_id']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> [
                'defaultOrder' => ['created_at' => SORT_DESC],
                'attributes' => [
                    'created_at',
                    'successCount',
                    'warningCount',
                    'dangerCount',
                    'fatalCount',
                ],
            ]
        ]);

        $this->load($params);
        if (!$this->validate()) return $dataProvider;

        if (!$this->date)
            $this->date = date('Y-m-d');
        else
            $this->rememberParams($params);

        $query->addSelect(["DATE_FORMAT(created_at, '%Y-%m-%d') as created_at"])
            ->andWhere(new Expression('DATE(created_at) = :date', [':date' => $this->date]));

        if ($this->website)
            $query->andWhere(['website_id' => (int) $this->website]);

        return $dataProvider;
    }


    public function getChartConfig()
    {
        $data = [];
        $query = (new Query())
            ->select(new Expression('*,
                       ROUND(AVG(elapsed_time), 3) avg_elapsed,
                       created_at as date,
                       DATE(created_at) as created_at'))
            ->from('parses')
            ->andWhere(['in', 'website_id', array_keys($this->websites)])
            ->andFilterWhere(['status' => LIBXML_ERR_NONE])
            ->groupBy(['rss_id', 'HOUR(created_at)'])
            ->orderBy(['id' => SORT_ASC]);

        if (!$this->date)
            $this->date = date('Y-m-d');
        $query->andWhere(['DATE(created_at)' => $this->date]);

        $statistics = $query->all();

        if (empty($statistics)) return $data;

        $success = [
            'backgroundColor' => "rgba(0,186,108,0.9)",
            'borderColor' => "rgba(0,186,108,1)",
            'pointBackgroundColor' => "rgba(0,186,108,1)",
            'pointBorderColor' => "#fff",
            'pointHoverBackgroundColor' => "#fff",
            'pointHoverBorderColor' => "rgba(255,99,132,1)",
            'hidden' => false,
        ];

        foreach ($statistics as $statistic) {
            $data['labels'][] = $this->getWebsiteLabel($statistic['website_id']);
            $data['addresses'][] = $this->getRssLabel($statistic['rss_id']);
            $parseDate = strtotime($statistic['date']);
            $data['dates'][] = date('H:00', $parseDate) . '-' . date('H:00', $parseDate + 60*60);
            $success['data'][] = $statistic['avg_elapsed'];
        }

        $data['datasets'] = [$success];

        return $data;
    }

    private function rememberParams(array $params): void
    {
        foreach ($params as $key => $param) {
            Yii::$app->response->cookies->add(new Cookie([
                'name' => $key,
                'value' => $param,
                'expire' => time() + 43200,
            ]));
        }
    }

    private function getRememberedParams($param)
    {
        return Yii::$app->request->cookies->getValue($param, null);
    }

    private function getWebsiteLabel($websiteId)
    {
        /** @var Website $website */
        $website =  $this->websites[$websiteId];
        return $website->name;
    }

    private function getRssLabel($rssId)
    {
        /** @var Rss $rss */
        $rss =  $this->rss[$rssId];
        return $rss->rss_address;
    }
}