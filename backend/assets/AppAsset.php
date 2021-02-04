<?php

namespace backend\assets;

use yii\web\AssetBundle;
use backend\assets\RemarkAsset;
/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css?v=1.3'
    ];
    public $js = [
        'js/site.js?v=1.3'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'backend\assets\RemarkAsset',
    ];
}
