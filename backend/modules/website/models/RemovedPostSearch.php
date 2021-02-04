<?php

namespace backend\modules\website\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use core\entities\Customer\Website\{Website, Post};
use core\entities\Customer\Website\PostRemovalReason;

/**
 * Class RemovedPostSearch
 * @package backend\modules\website\models
 */
class RemovedPostSearch extends Model
{
    public ?string $id = null;
    public ?string $website = null;
    public ?string $title = null;
    public ?string $lang = null;
    public ?string $created_at = null;
    public ?string $reason = null;
    public ?string $user = null;

    private ?array $posts = [];
    private ?array $websites = [];

    public function __construct($config = [])
    {
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['website', 'title', 'reason', 'created_at', 'id'], 'safe'],
            ['created_at', 'date', 'format' => 'php:Y-m-d']
        ];
    }

    public function search($params)
    {
        $query = PostRemovalReason::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> [
                'defaultOrder' => ['created_at' => SORT_DESC],
                'attributes' => [
                    'id',
                    'created_at'
                ],
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) return $dataProvider;

        if (!$this->created_at) $this->created_at = date('Y-m-d');
        $query->andWhere(['DATE(created_at)' => $this->created_at]);

        if ($this->id) $query->andFilterWhere(['id' => $this->id]);

        if ($this->reason) $query->andWhere(['like', 'reason', $this->reason]);

        if ($this->title) {
            $this->posts = array_map(fn($post) => $post->id, Post::find()->andWhere(['like', 'title', $this->title])->all());
            $query->andWhere(['post_id' => $this->posts]);
        }

        if ($this->website) {
            $this->websites = array_map(fn($website) => $website->id, Website::find()->andWhere(['like', 'name', $this->website])->all());
            $this->posts = array_map(fn($post) => $post->id, Post::find()->andWhere(['website_id' => $this->websites])->all());

            $query->andWhere(['in', 'post_id', $this->posts]);
        }

        return $dataProvider;
    }
}