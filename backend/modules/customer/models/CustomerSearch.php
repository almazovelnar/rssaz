<?php

namespace backend\modules\customer\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use core\entities\Customer\Customer;

/**
 * CustomerSearch represents the model behind the search form of `core\entities\Customer\Customer`.
 */
class CustomerSearch extends Model
{
    public $email;
    public $status;
    public $date_from;
    public $date_to;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['date_from', 'date_to'], 'date', 'format' => 'php:Y-m-d'],
            [['email', 'status'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
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
        $query = Customer::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> [
                'defaultOrder' => ['created_at' => SORT_DESC],
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['>=', 'created_at', $this->date_from ? strtotime($this->date_from . ' 00:00:00') : null])
            ->andFilterWhere(['<=', 'created_at', $this->date_to ? strtotime($this->date_to . ' 23:59:59') : null]);

        return $dataProvider;
    }
}
