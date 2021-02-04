<?php

return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=mysql;dbname=rss',
            'username' => 'root',
            'password' => 'secret',
            'charset' => 'utf8',
        ],
        'clickhouse' => [
            'class' => 'kak\clickhouse\Connection',
            'dsn' => 'clickhouse',
            'port' => '8123',
            'database' => 'rss',
            'username' => 'default',
            'password' => '',
            'transportClass' => core\http\RssCurlTransport::class,
            /*'enableSchemaCache' => true,
            'schemaCache' => 'cache',
            'schemaCacheDuration' => 86400*/
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'cache' => ['class' => 'yii\redis\Cache'],
        'redis' => [
            'class' => 'yii\redis\Connection',
            'hostname' => 'redis',
            'port' => 6379,
            'database' => 0
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
    ],
];
