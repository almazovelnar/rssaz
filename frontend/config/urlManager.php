<?php

return [
    'hostInfo' => $params['frontendHostInfo'],
    'class' => 'yii\web\UrlManager',
    'baseUrl' => '',
    'enablePrettyUrl' => true,
    'showScriptName' => false,
    'rules' => [
        '' => 'site/index',
        '<slug:about>' => 'static/view',
        '<slug:contacts>' => 'static/view',
        '<slug:reviews>' => 'reviews/index',
        '<slug:privacy-policy>' => 'static/view',

        'category/<slug:[\w\-]+>/page/<page:\d+>' => 'category/view',
        'category/<slug:[\w\-]+>' => 'category/view',

        // Latest posts
        'latest-posts/page/<page:\d+>' => 'post/index',
        'latest-posts' => 'post/index',

        // Search
        'search/<q:.+>/page/<page:\d+>' => 'search/index',
        'search/<q:.+>' => 'search/index',

        '<_c:[\w\-]+>' => '<_c>/index',
        '<_c:[\w\-]+>/<id:\d+>' => '<_c>/view',
        '<_c:[\w\-]+>/<_a:[\w\-]+>' => '<_c>/<_a>',
        '<_c:[\w\-]+>/<id:\d+>/<_a:[\w\-]+>' => '<_c>/<_a>',
    ],
];