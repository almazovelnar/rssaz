<?php

return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'bootstrap' => [
        'common\bootstrap\SetUp', 'queue', 'log'
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'timeZone' => 'Asia/Baku',
    'components' => [
        'formatter' => [
            'class'           => 'yii\i18n\Formatter',
            'timeZone'        => 'GMT+04:00',
            'defaultTimeZone' => 'Asia/Baku',
            'datetimeFormat' => 'php:j-F-Y H:i'
        ],
        'queue' => [
            'class' => 'yii\queue\redis\Queue',
            'ttr' => 600,
            'redis' => 'redis', // Компонент подключения к Redis или его конфиг
            'channel' => 'rss', // Ключ канала очереди
            'as log' => 'yii\queue\LogBehavior',
        ],
        'config' => [
            'class' => 'core\components\Config',
            'cache' => false,
        ],
        'storage' => [
            'class' => 'core\components\Storage\Storage'
        ]
    ],
];
