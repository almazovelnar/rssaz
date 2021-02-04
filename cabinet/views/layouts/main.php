<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Url;
use yii\helpers\Html;
use common\widgets\Alert;
use cabinet\assets\AppAsset;

AppAsset::register($this);

/** @var \core\entities\Customer\Customer $user */
$user = Yii::$app->user->identity;

$classIndex = Yii::$app->controller->id == 'site' ? "index" : "";

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<header class="dashboard-header">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="inner flex">
                    <div class="menu-toggler visible-991"><img src="<?= Yii::getAlias('@web/') ?>images/bars-white.svg"></div>

                    <div class="logo-links flex">
                        <div class="logo"><a href="/"><img src="<?= Yii::getAlias('@web/') ?>images/logo-white.svg"></a></div>

                        <ul class="hidden-991">
                            <li><a href="<?= Url::to(['website/index']) ?>">Saytlarım</a></li>
                            <li><a href="<?= Url::to(['validator/index']) ?>">RSS Validator</a></li>
                            <li><a href="<?= Url::to(['requirements/index']) ?>">Texniki tələblər</a></li>
                        </ul>
                    </div>
                    <!-- Logo / Links-->

                    <div class="user-info flex hidden-575">
                        <div class="add-website hidden-991">
                            <a class="flex" href="<?= Url::to(['website/create']) ?>">
                                Sayt əlavə et<i class="material-icons">add_circle_outline</i>
                            </a>
                        </div>

                        <div class="user flex">
                            <div class="actions">
                                <p class="name"><?= $user->getFullName() ?></p>
                                <a href="<?= Url::to(['profile/index']) ?>">Edit profile</a>
                            </div>

                            <div class="user-image cover-center" style="background-image: url(<?= Yii::$app->storage->customer->getThumb(50, $user->getAvatar()) ?>)">
                                <div class="overlay flex-center">
                                    <a class="logout" href="<?= Url::to(['auth/auth/logout']) ?>"><i class="material-icons">clear</i></a>
                                </div>
                            </div>
                        </div>
                        <!-- User-->
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<!-- Header-->

<div class="side-menu-wrapper visible-991">
    <div class="close-menu"><img src="<?= Yii::getAlias('@web/') ?>images/close.svg"></div>

    <div class="side-menu">
        <div class="user flex">
            <div class="actions">
                <p class="name"><?= $user->getFullName() ?></p>
                <a href="<?= Url::to(['profile/index']) ?>">Edit profile</a>
            </div>

            <div class="user-image cover-center" style="background-image: url(<?= Yii::$app->storage->customer->getThumb(50, $user->getAvatar()) ?>)">
                <div class="overlay flex-center"><a class="logout" href="<?= Url::to(['auth/auth/logout']) ?>"><i class="material-icons">clear</i></a></div>
            </div>
        </div>
        <!-- User-->

        <ul class="dashboard-links">
            <li><a href="/">Dashboard</a></li>
            <li><a href="<?= Url::to(['website/index']) ?>">Saytlarım</a></li>
            <li><a href="<?= Url::to(['validator/index']) ?>">RSS Validator</a></li>
        </ul>

        <div class="add-website"><a class="flex" href="<?= Url::to(['website/create']) ?>">Sayt əlavə et<i class="material-icons">add_circle_outline</i></a></div>
    </div>
</div>

<main class="dashboard-content <?= $classIndex ?>">
    <?= Alert::widget() ?>

    <?= $content ?>
</main>

<footer class="dashboard-footer">
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="inner flex">
                    <div class="copyrights">
                        <p>RSS.az</p>
                        <p>Copyright © <?= date('Y') ?> Bütün hüquqlar qorunur.</p>
                    </div>

                    <div class="reference">
                        <a href="http://rss.az/privacy-policy" target="_blank">Məxfilik siyasəti</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
