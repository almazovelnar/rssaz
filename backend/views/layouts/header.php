<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View */
/* @var $content string */

/** @var \core\entities\User $user */
$user = Yii::$app->user->identity;
?>

<nav class="site-navbar">
    <div class="navbar-header">
        <div class="navbar-brand navbar-brand-center site-gridmenu-toggle" style="width: 100%; background: #0a73bb;">
            <?= Html::a(
                Html::img(Yii::getAlias('@web/remark/assets/images/logo.png'), ['class' => 'navbar-brand-logo', 'title' => Yii::$app->name]) .
                '<span class="navbar-brand-text"> ' . Yii::$app->name . '</span>', Yii::$app->homeUrl
                , ['class' => 'logo']) ?>
        </div>
    </div>
    <div id="nav-icon4" class="fold-show nav-icon4" data-toggle="menubar" role="button">
        <span></span>
        <span></span>
        <span></span>
    </div>
</nav>