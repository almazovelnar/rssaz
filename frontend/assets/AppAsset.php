<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/style.css?v=1.1'
    ];
    public $js = [
        'js/jquery.lazy.js',
        'js/common.js',
        'js/detecter.js'
    ];
    public $depends = [
        'yii\web\YiiAsset'
    ];
}
