<?php

return [
    'class' => 'yii\web\UrlManager',
    'baseUrl' => '',
    'hostInfo' => $params['apiHostInfo'],
    'enablePrettyUrl' => true,
    'showScriptName' => false,
    'rules' => [
        ''  => 'site/index',
        'data/<hash:\w+>' => 'data/get',
        'code/<hash:\w+>' => 'code/get',
        'amp/code/<hash:\w+>.html' => 'code/get-amp',
    ],
];