<?php

namespace cabinet\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons',
        'css/vendor.css',
        'css/style.css',
    ];
    public $js = [
        'js/index.bundle.js',
        'js/index.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
