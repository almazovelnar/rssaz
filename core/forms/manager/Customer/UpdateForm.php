<?php

namespace core\forms\manager\Customer;

use core\entities\Customer\Customer;
use core\helpers\CustomerHelper;
use yii\base\Model;

class UpdateForm extends Model
{
    public $name;
    public $surname;
    public $email;
    public $thumbFile;
    public $status;

    private $_customer;

    public function __construct(Customer $customer, array $config = [])
    {
        $this->name = $customer->name;
        $this->surname = $customer->surname;
        $this->email = $customer->email;
        $this->status = $customer->status;
        $this->_customer = $customer;
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['name', 'surname', 'email', 'status'], 'required'],
            [['email', 'name', 'surname'], 'string', 'max' => 30],
            [['email'], 'email'],
            [['email'], 'trim'],
            ['email', 'unique', 'targetClass' => Customer::class, 'filter' => ['<>', 'id', $this->_customer->id]],
            ['status', 'in', 'range' => array_keys(CustomerHelper::statusesList())],
            ['thumbFile', 'image']
        ];
    }
}