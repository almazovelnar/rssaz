<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=yii2advanced',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
        ],
        'redis' => [
            'class' => 'yii\redis\Connection',
            'hostname' => 'localhost',
            'port' => 6379,
            'database' => 0
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'nex\graylog\GraylogTarget',
                    'levels' => ['error', 'warning'],
                    'categories' => ['application'],
                    'logVars' => [], // This prevent yii2-debug from crashing ;)
                    'host' => 'rss.az',
                    'facility' => 'phpd',
                    'additionalFields' => [
                        'request_dump' => function($app) {
                            /** @var \yii\base\Application $app */
                            return \core\helpers\CommonHelper::grayLogRequisites($app);
                        },
                        'tag' => 'graylog2.php.exeptions'
                    ]
                ],
            ],
        ],
    ],
];
