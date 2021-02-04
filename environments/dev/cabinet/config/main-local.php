<?php

$config = [
    'components' => [
        'request' => [
            'cookieValidationKey' => 'asd**#%^&*#(%hdg65r4as56d(&@13',
        ],
    ],
];

if (!YII_ENV_TEST) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        'allowedIPs' => ['127.0.0.1', '::1', '192.168.*', '10.*', '172.*'],
    ];

    // ClickHouse profiling
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        'allowedIPs' => ['127.0.0.1', '::1', '192.168.*', '10.*', '172.*'],
        'panels' => [
            'clickhouse' => [
                'class' => 'kak\clickhouse\debug\Panel',
                'db' => 'clickhouse'
            ],
        ]
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
