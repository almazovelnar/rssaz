<?php

namespace backend\modules\page\models;

use Yii;
use yii\db\ActiveQuery;
use core\entities\Page\Page;
use yii\data\ActiveDataProvider;

/**
 * PageSearch represents the model behind the search form of `core\entities\Page\Page`.
 */
class PageSearch extends Page
{
    /**
     * @var string
     */
    public $title;
    /**
     * @var bool
     */
    public $status;

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id', 'status'], 'integer'],
            [['title', 'status'], 'safe'],
        ];
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Page::find()->alias('p')->joinWith(['translations t' => function (ActiveQuery $query) {
            return $query->andOnCondition(['language' => Yii::$app->language]);
        }]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['status' => $this->status]);
        $query->andFilterWhere(['like', 't.title', $this->title]);

        return $dataProvider;
    }
}
