<?php

namespace core\forms\cabinet\Profile;

use core\entities\Customer\Customer;
use yii\base\Model;

class UpdateForm extends Model
{
    public $name;
    public $surname;
    public $thumbFile;

    public function __construct(Customer $customer, array $config = [])
    {
        $this->name = $customer->name;
        $this->surname = $customer->surname;
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['name', 'surname'], 'required'],
            [['name', 'surname'], 'string', 'max' => 30],
            ['thumbFile', 'image']
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Adınız',
            'surname' => 'Soyadınız',
        ];
    }
}