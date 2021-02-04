<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-frontend',
    'aliases' => [
        '@static' => $params['staticHostInfo']
    ],
    'language' => 'az',
    'name' => 'Rss.az',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['\frontend\bootstrap\SetUp'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'request' => [
            'class' => 'core\components\Language\LanguageHttpRequest',
            //'class' => 'yii\web\Request',
            'csrfParam' => '_csrf-frontend',
        ],
        'user' => [
            'identityClass' => 'core\entities\Customer\Customer',
            'enableAutoLogin' => false,
            'identityCookie' => ['name' => '_identity', 'httpOnly' => true, 'domain' => $params['frontendCookieDomain']],
        ],
        /*'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced',
            'cookieParams' => [
                'domain' => $params['frontendCookieDomain'],
                'httpOnly' => true,
            ],
        ],*/
        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                ],
            ],
        ],
        'view' => [
            'class' => 'frontend\yii\web\View'
        ],
        'log' =>
            YII_DEBUG ?
                [] :
                [
                    'traceLevel' => 3,
                    'targets' => [
                        'graylog' => [
                            'class' => 'common\log\GraylogTarget',
                            'except' => ['yii\web\HttpException:404', 'yii\web\HttpException:400', 'yii\debug\Module*'],
                            'levels' => ['error', 'warning'],
                            'logVars' => []
                        ],
                        [
                            'class' => 'common\log\GraylogProfilingTarget',
                            'levels' => ['profile'],
                        ],
                    ],
                ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'frontendUrlManager' => require __DIR__ . '/urlManager.php',
        'cabinetUrlManager' => require __DIR__ . '/../../cabinet/config/urlManager.php',
        'backendUrlManager' => require __DIR__ . '/../../backend/config/urlManager.php',
        'apiUrlManager'      => require __DIR__ . '/../../api/config/urlManager.php',
        'urlManager' => function() {
            return Yii::$app->get('frontendUrlManager');
        },
    ],
    'params' => $params,
];
