<?php

return [
    'class' => 'yii\web\UrlManager',
    'baseUrl' => '',
    'hostInfo' => $params['cabinetHostInfo'],
    'enablePrettyUrl' => true,
    'showScriptName' => false,
    'rules' => [
        '' => 'site/index',
        'signup' => 'auth/signup/request',
        '<_a:login|logout>' => 'auth/auth/<_a>',
        'news/prioritize/<id:\d+>' => 'news/prioritize',
        'code/preview/<id:\d+>' => 'code/preview',
        'news/<website_id:\d+>' => 'news/index',
        'requirements' => 'static/requirements',
        '<_c:[\w\-]+>' => '<_c>/index',
        '<_c:[\w\-]+>/<id:\d+>' => '<_c>/view',
        '<_c:[\w\-]+>/<_a:[\w\-]+>' => '<_c>/<_a>',
        '<_c:[\w\-]+>/<id:\d+>/<_a:[\w\-]+>' => '<_c>/<_a>',
    ],
];