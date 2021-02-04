<?php

use backend\components\auth\Rbac;

$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-backend',
    'name' => 'Rss.az Admin',
    'language' => 'en',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['backend\bootstrap\SetUp'],
    'aliases' => [
        '@static' => $params['staticHostInfo'],
    ],
    'modules' => [
        'category' => [
            'class' => 'backend\modules\category\Module',
        ],
        'user' => [
            'class' => 'backend\modules\user\Module',
        ],
        'page' => [
            'class' => 'backend\modules\page\Module',
        ],
        'customer' => [
            'class' => 'backend\modules\customer\Module',
        ],
        'website' => [
            'class' => 'backend\modules\website\Module',
        ],
        'config' => [
            'class' => 'backend\modules\config\Module',
        ],
        'diagnostics' => [
            'class' => 'backend\modules\diagnostics\Module',
        ],
        'statistics' => [
            'class' => 'backend\modules\statistics\Module',
        ],
        'antifraud' => [
            'class' => 'backend\modules\antifraud\Module',
        ],
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
        ],
        'user' => [
            'identityClass' => 'core\entities\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
        ],
        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                ],
            ],
        ],
        'authManager' => [
            'class' => 'core\components\AuthManager',
            'defaultRoles' => ['?'],
            'itemFile'       => '@backend/components/auth/files/items.php',
            'assignmentFile' => '@backend/components/auth/files/assignments.php',
            'ruleFile'       => '@backend/components/auth/files/rules.php'
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'assetManager' => [
            'bundles' => [
                'yii\bootstrap\BootstrapAsset'  => false,
                'yii\web\JqueryAsset'           => [
                    'js' => ['/remark/global/vendor/jquery/jquery.js']
                ],
                'yii\web\YiiAsset' => ['depends' => ['backend\assets\RemarkAsset']]
            ],
        ],
        'backendUrlManager' => require __DIR__ . '/urlManager.php',
        'cabinetUrlManager' => require __DIR__ . '/../../cabinet/config/urlManager.php',
        'frontendUrlManager' => require __DIR__ . '/../../frontend/config/urlManager.php',
        'apiUrlManager'      => require __DIR__ . '/../../api/config/urlManager.php',
        'urlManager' => function() {
            return Yii::$app->get('backendUrlManager');
        },
    ],
    'as access' => [
        'class' => 'yii\filters\AccessControl',
        'except' => ['site/login', 'site/error'],
        'rules' => [
            [
                'allow' => true,
                'permissions' => [Rbac::PERMISSION_ADMIN_PANEL],
            ],
        ],
        'denyCallback' => function () {
            if (!Yii::$app->user->isGuest) {
                Yii::$app->user->logout();
            }

            return Yii::$app->response->redirect(['site/login']);
        },
    ],
    'params' => $params,
];
