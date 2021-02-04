<?php

namespace backend\modules\user\models;

use core\entities\User;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\user\helpers\UserHelper;

/**
 * UserSearch represents the model behind the search form of `core\entities\User`.
 */
class UserSearch extends Model
{
    public $username;
    public $email;
    public $role;
    public $status;
    public $date_from;
    public $date_to;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['status', 'integer'],
            [['username', 'email'], 'safe'],
            ['role', 'in', 'range' => array_keys(UserHelper::rolesList())],
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
        $query = User::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'role' => $this->role,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['>=', 'created_at', $this->date_from ? strtotime($this->date_from . ' 00:00:00') : null])
            ->andFilterWhere(['<=', 'created_at', $this->date_to ? strtotime($this->date_to . ' 23:59:59') : null]);

        return $dataProvider;
    }
}
