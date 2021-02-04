<?php

namespace backend\modules\website\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\web\{Cookie, Request, Response};
use core\entities\Customer\Website\Post;
use core\repositories\interfaces\WebsiteRepositoryInterface;


/**
 * Class PostSearch
 * @package backend\modules\website\models
 */
class PostSearch extends Model
{
    public const FILTER_REMEMBER_KEY = 'posts_filter_';

    public ?string $website = null;
    public ?string $title = null;
    public ?string $lang = null;
    public ?string $date_from = null;
    public ?string $date_to = null;

    public ?string $views = null;
    public ?string $clicks = null;
    public ?string $priority = null;

    public ?array $websites = [];

    private WebsiteRepositoryInterface $websiteRepository;
    private Response $response;
    private Request $request;

    public function __construct(
        WebsiteRepositoryInterface $websiteRepository,
        Response $response,
        Request $request,
        $config = []
    )
    {
        parent::__construct($config);

        $this->websiteRepository = $websiteRepository;
        $this->response = $response;
        $this->request = $request;

        $this->restoreFilters();
    }

    public function formName()
    {
        return '';
    }

    public function rules()
    {
        return [
            [['date_from', 'date_to'], 'date', 'format' => 'php:Y-m-d'],
            [['views', 'clicks', 'priority'], 'integer'],
            [['customer', 'website', 'title', 'lang'], 'safe'],
        ];
    }

    public function search($params)
    {
        $query = Post::find()->andWhere(['status' => Post::STATUS_ACTIVE]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['created_at' => SORT_DESC],
                'attributes' => ['title', 'created_at', 'views', 'clicks', 'priority'],
            ]
        ]);

        $this->load($params);
        if (!$this->validate()) return $dataProvider;

        if ($this->website)
            $this->websites = array_map(fn ($website) => $website->id, $this->websiteRepository->all(['address' => $this->website]));

        $query->andFilterWhere(['lang' => $this->lang]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['in', 'website_id', $this->websites])
            ->andFilterWhere(['>=', 'toDateTime(created_at)', $this->date_from ? strtotime($this->date_from . ' 00:00:00') : null])
            ->andFilterWhere(['<=', 'toDateTime(created_at)', $this->date_to ? strtotime($this->date_to . ' 23:59:59') : null])
            ->andFilterWhere(['>=', 'views', (int) $this->views])
            ->andFilterWhere(['>=', 'clicks', (int) $this->clicks]);

        if ($this->priority) $query->andFilterWhere(['priority' => (int) $this->priority]);

        $this->rememberFilters();

        return $dataProvider;
    }

    private function restoreFilters(): void
    {
        $cookies = $this->request->cookies;

        foreach ($this as $filter => $value) {
            if (!$cookies->has(($key = self::FILTER_REMEMBER_KEY . $filter)))
                continue;
            $this->{$filter} = $cookies->getValue($key, null);
        }
    }

    private function rememberFilters(): void
    {
        foreach ($this as $filter => $value) {
            $this->response->cookies->add(new Cookie([
                'name'   => self::FILTER_REMEMBER_KEY . $filter,
                'value'  => $value,
                'expire' => time() + (86400 * 365),
            ]));
        }
    }
}