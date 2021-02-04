<?php

namespace backend\modules\diagnostics\models;

use yii\base\Model;
use yii\db\Expression;
use core\entities\Parse\Parse;
use yii\data\ActiveDataProvider;
use core\helpers\DiagnosticsHelper;

/**
 * Class DiagnosticsSearch
 * @package backend\modules\diagnostics\models
 */
class DiagnosticsSearch extends Model
{
    public ?string $date = null;
    public ?string $website = null;
    public ?string $status = null;

    public function formName(): string
    {
        return '';
    }

    public function rules(): array
    {
        return [
            ['website', 'integer'],
            ['date', 'date', 'format' => 'php: Y-m-d'],
            ['status', 'in', 'range' => array_keys(DiagnosticsHelper::statusesList('success'))]
        ];
    }

    public function search(array $params = [])
    {
        $query = Parse::find()->andWhere(['!=', 'status', LIBXML_ERR_NONE]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> [
                'defaultOrder' => ['created_at' => SORT_DESC],
                'attributes' => [
                    'created_at',
                ],
            ]
        ]);

        $this->load($params);
        if (!$this->validate()) return $dataProvider;

        if (!$this->date)
            $this->date = date('Y-m-d');

        $query->andWhere(new Expression('DATE(created_at) = :date', [':date' => $this->date]));

        if ($this->status != null) {
            $query->andWhere(['status' => $this->status]);
        }

        if ($this->website) {
            $query->andWhere(['website_id' => (int) $this->website]);
        }

        return $dataProvider;
    }
}