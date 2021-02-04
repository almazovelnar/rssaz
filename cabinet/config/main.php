<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-cabinet',
    'language' => 'az',
    'name' => 'Rss.az Dashboard',
    'aliases' => [
        '@static' => $params['staticHostInfo']
    ],
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['\cabinet\bootstrap\SetUp'],
    'controllerNamespace' => 'cabinet\controllers',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-cabinet',
        ],
        'user' => [
            'identityClass' => 'core\entities\Customer\Customer',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity', 'httpOnly' => true, 'domain' => $params['frontendCookieDomain']],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced',
            'cookieParams' => [
                'domain' => $params['frontendCookieDomain'],
                'httpOnly' => true,
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'reCaptcha' => [
            'class' => 'himiklab\yii2\recaptcha\ReCaptchaConfig',
            'siteKeyV2' => 'your siteKey v2',
            'secretV2' => 'your secret key v2',
            'siteKeyV3' => '6LeE4LQUAAAAAOrvEz4Zm-yuI4MV7Gf_egvDl5dn',
            'secretV3' => '6LeE4LQUAAAAAErRclnhqW0wn3gkzaAeAitVE4cI',
        ],
        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                ],
            ],
        ],
        'cabinetUrlManager' => require __DIR__ . '/urlManager.php',
        'backendUrlManager' => require __DIR__ . '/../../backend/config/urlManager.php',
        'frontendUrlManager' => require __DIR__ . '/../../frontend/config/urlManager.php',
        'apiUrlManager'      => require __DIR__ . '/../../api/config/urlManager.php',
        'urlManager' => function() {
            return Yii::$app->get('cabinetUrlManager');
        },
    ],
    'as access' => [
        'class' => 'yii\filters\AccessControl',
        'except' => [
            'auth/auth/login',
            'auth/signup/request',
            'auth/signup/confirm',
            'auth/reset/request',
            'auth/reset/confirm'
        ],
        'rules' => [
            [
                'allow' => true,
                'roles' => ['@'],
            ],
        ],
        'denyCallback' => function () {
            return Yii::$app->response->redirect(['auth/auth/login']);
        },
    ],
    'params' => $params,
];
