<?php

namespace backend\modules\user\helpers;

use core\entities\User;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * Class UserHelper
 * @package backend\modules\user\helpers
 */
class UserHelper
{
    /**
     * @return array
     */
    public static function rolesList(): array
    {
        return [
            User::ROLE_ADMIN => 'Administrator',
            User::ROLE_MODERATOR => 'Moderator'
        ];
    }

    /**
     * @param string $role
     * @return string
     */
    public static function roleLabel(string $role): string
    {
        switch ($role) {
            case User::ROLE_ADMIN:
                $class = 'badge badge-danger';
                break;
            case User::ROLE_MODERATOR:
                $class = 'badge badge-info';
                break;
            default:
                $class = 'badge badge-default';
                break;
        }

        return Html::tag('span', ArrayHelper::getValue(self::rolesList(), $role), [
            'class' => $class
        ]);
    }
}