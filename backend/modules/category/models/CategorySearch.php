<?php

namespace backend\modules\category\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use core\entities\Category\Category;

/**
 * PageSearch represents the model behind the search form of `core\entities\Category\Category`.
 */
class CategorySearch extends Model
{
    public $name;
    public $status;
    public $show_in_menu;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status', 'show_in_menu'], 'boolean'],
            ['name', 'string'],
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
        $query = Category::find()->andWhere(['>', 'depth', 0])->orderBy('lft');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['status' => $this->status, 'show_in_menu' => $this->show_in_menu]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
