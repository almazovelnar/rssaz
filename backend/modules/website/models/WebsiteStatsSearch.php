<?php

namespace backend\modules\website\models;

use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;
use core\entities\Customer\Website\Website;
use core\repositories\interfaces\WebsiteRepositoryInterface;

/**
 * Class WebsiteStatsSearch
 * @package backend\modules\website\models
 */
class WebsiteStatsSearch extends Model
{
    /** @var string|int $website */
    public $website;
    private WebsiteRepositoryInterface $websiteRepository;

    public function __construct(WebsiteRepositoryInterface $websiteRepository)
    {
        parent::__construct([]);

        $this->websiteRepository = $websiteRepository;
    }

    public function rules(): array
    {
        return [
            ['website', 'integer'],
        ];
    }

    public function formName(): string
    {
        return '';
    }

    public function getWebsites(): array
    {
        return ArrayHelper::map(Website::find()
            ->where(['!=', 'name', 'rss.az'])
            ->andWhere(['status' => Website::STATUS_ACTIVE])
            ->asArray()
            ->all(), 'id', 'name');
    }

    public function search(array $params)
    {
        $query = $this->websiteRepository
            ->query()
            ->where(['w.status' => Website::STATUS_ACTIVE])
            ->orderBy('w.rate_actual DESC');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => [
                    'rate_min',
                ],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) return $dataProvider;

        if (!empty($this->website))
            $query->andWhere(['w.id' => (int) $this->website]);

        return $dataProvider;
    }
}