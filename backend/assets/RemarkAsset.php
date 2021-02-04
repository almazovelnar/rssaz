<?php

namespace backend\assets;

use yii\web\AssetBundle;
use yii\web\JqueryAsset;

/**
 * Class RemarkAsset
 * @package backend\assets
 */
class RemarkAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $basePath = '@webroot/remark';
    /**
     * @var string
     */
    public $baseUrl = '@web/remark';
    /**
     * @var array
     */
    public $css = [
        'global/css/bootstrap.min.css',
        'global/css/bootstrap-extend.min.css',
        'assets/css/site.min.css?v=1.12',

        'global/vendor/animsition/animsition.css',
        'global/vendor/switchery/switchery.css',
        'global/vendor/slidepanel/slidePanel.css',
        'global/vendor/flag-icon-css/flag-icon.css',

        'global/fonts/web-icons/web-icons.min.css',
        'global/fonts/brand-icons/brand-icons.min.css',
        'global/fonts/material-design/material-design.min.css',

        'https://fonts.googleapis.com/css?family=Roboto:300,400,500,300italic'
    ];
    /**
     * @var array
     */
    public $js = [
        'global/vendor/babel-external-helpers/babel-external-helpers.js',
        'global/vendor/popper-js/umd/popper.min.js',
        'global/vendor/bootstrap/bootstrap.js',
        'global/vendor/mousewheel/jquery.mousewheel.js',
        'global/vendor/asscrollbar/jquery-asScrollbar.js',
        'global/vendor/asscrollable/jquery-asScrollable.js',
        'global/vendor/ashoverscroll/jquery-asHoverScroll.js',

        'global/js/Component.js',
        'global/js/Plugin.js',
        'global/js/Base.js',
        'global/js/Config.js',
        'global/js/material.js',

        'assets/js/Section/Menubar.js',
        'assets/js/Section/GridMenu.js',
        'assets/js/Section/Sidebar.js',
        'assets/js/Section/PageAside.js',
        'assets/js/menu.js',
    ];

    /**
     * @var array
     */
    public $depends = [
        JqueryAsset::class
    ];
}