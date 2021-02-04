<?php

namespace core\helpers;

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use core\entities\Customer\Customer;
use core\entities\Customer\Website\Website;

class CustomerHelper
{
    public static function statusesList()
    {
        return [
            Customer::STATUS_ACTIVE => 'Active',
            Customer::STATUS_BLOCKED => 'Inactive',
            Customer::STATUS_WAIT => 'Wait',
        ];
    }

    public static function statusName($status)
    {
        return ArrayHelper::getValue(self::statusesList(), $status);
    }

    public static function statusLabel($status)
    {
        switch ($status) {
            case Customer::STATUS_WAIT:
                $class = 'badge badge-warning';
                break;
            case Customer::STATUS_ACTIVE:
                $class = 'badge badge-success';
                break;
            case Customer::STATUS_BLOCKED:
                $class = 'badge badge-danger';
                break;
            default:
                $class = 'badge badge-default';
                break;
        }

        return Html::tag('span', ArrayHelper::getValue(self::statusesList(), $status), [
            'class' => $class
        ]);
    }

    public static function websitesList(Customer $customer)
    {
        return ArrayHelper::map($customer->getWebsites()->where(['customer_id' => $customer->id, 'status' => Website::STATUS_ACTIVE])->asArray()->all(), 'id', 'name');
    }
}