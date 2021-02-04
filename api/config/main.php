<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-api',
    'language' => 'az',
    'name' => 'Rss.az Api',
    'aliases' => [
        '@static' => $params['staticHostInfo']
    ],
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'api\controllers',
    'bootstrap' => ['api\bootstrap\SetUp'],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-api',
        ],
        'user' => [
            'identityClass' => 'core\entities\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-api', 'httpOnly' => true],
        ],
        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                ],
            ],
        ],
        'errorHandler' => ['errorAction' => 'site/index'],
        'log' =>
            YII_DEBUG ?
                [] :
                [
                    'traceLevel' => 3,
                    'targets' => [
                        'graylog' => [
                            'class' => 'common\log\GrayLogTarget',
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
        'apiUrlManager' => require __DIR__ . '/urlManager.php',
        'backendUrlManager' => require __DIR__ . '/../../backend/config/urlManager.php',
        'frontendUrlManager' => require __DIR__ . '/../../frontend/config/urlManager.php',
        'urlManager' => function() {
            return Yii::$app->get('apiUrlManager');
        },
    ],
    'params' => $params,
];
