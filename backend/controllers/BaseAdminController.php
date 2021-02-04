<?php

namespace backend\controllers;

use yii\web\Controller;
use yii\filters\AccessControl;
use backend\components\auth\Rbac;

/**
 * Class BaseAdminController
 * @package backend\controllers
 */
class BaseAdminController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['delete'],
                'rules' => [
                    [
                        'allow' => true,
                        'permissions' => [Rbac::PERMISSION_DELETE_RECORD],
                    ]
                ],
            ]
        ];
    }
}