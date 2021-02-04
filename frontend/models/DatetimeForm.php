<?php

namespace frontend\models;

use yii\base\Model;

class DatetimeForm extends Model
{
    /**
     * @var string
     */
    public $date;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            ['date', 'required'],
            ['date', 'datetime', 'format' => 'php: Y-m-d H:i:s', 'max' => time()]
        ];
    }
}