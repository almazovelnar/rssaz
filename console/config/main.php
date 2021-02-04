<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-console',
    'name' => 'Rss.az',
    'language' => 'az',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'console\controllers',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'controllerMap' => [
        'fixture' => [
            'class' => 'yii\console\controllers\FixtureController',
            'namespace' => 'common\fixtures',
          ],
    ],
    'components' => [
        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                ],
            ],
        ],
        'backendAuthManager' => [
            'class' => 'core\components\AuthManager',
            'defaultRoles' => ['admin'],
            'itemFile'       => '@backend/components/auth/files/items.php',
            'assignmentFile' => '@backend/components/auth/files/assignments.php',
            'ruleFile'       => '@backend/components/auth/files/rules.php'
        ],
        'backendUrlManager' => require __DIR__ . '/../../backend/config/urlManager.php',
        'frontendUrlManager' => require __DIR__ . '/../../frontend/config/urlManager.php',
        'cabinetUrlManager' => require __DIR__ . '/../../cabinet/config/urlManager.php',
        'apiUrlManager'      => require __DIR__ . '/../../api/config/urlManager.php',
    ],
    'params' => $params,
];
