<?php

/** @var \core\entities\Category\Category[] $categories */

use core\helpers\WebsiteHelper;
use yii\helpers\Url;
?>
<header>
    <div class="container">
        <div class="row">
            <div class="col-12 flex">
                <div class="menu-toggler visible-1200">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>

                <div class="logo-langs flex">
                    <div class="logo">
                        <a href="/">
                            <img src="/images/logo.png">
                        </a>
                    </div>

                    <div class="dropdown langs hidden-1200">
                        <a class="dropdown-toggle" href="#" role="button" id="langs" data-toggle="dropdown">
                            <?= Yii::$app->language ?>
                        </a>
                        <div class="dropdown-menu">
                            <?php foreach (WebsiteHelper::otherLanguages() as $locale => $language): ?>
                                 <a class="dropdown-item" href="<?= WebsiteHelper::makeLanguageUrl($locale) ?>"><?= $locale ?></a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <nav class="main-menu hidden-1200">
                    <ul>
                        <li><a href="<?= Url::to(['post/index']) ?>"><?= Yii::t('main', 'latest_news') ?></a></li>
                        <?php foreach ($categories as $category): ?>
                            <li>
                                <a href="<?= Url::to(['category/view', 'slug' => $category->slug]) ?>">
                                    <?= $category->multilingual->title ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </nav>

                <div class="login">
                    <a href="<?= Yii::$app->cabinetUrlManager->createAbsoluteUrl(['site/index']) ?>">
                        <?= Yii::t('main', 'login') ?>
                        <i class="icon-angle-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="mobile-sidebar">
        <div class="sidebar-body">
            <ul>
                <li><a href="<?= Url::to(['post/index']) ?>"><?= Yii::t('main', 'latest_news') ?></a></li>
                <?php foreach ($categories as $category): ?>
                    <li>
                        <a href="<?= Url::to(['category/view', 'slug' => $category->slug]) ?>">
                            <?= $category->multilingual->title ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>

            <ul class="langs">
                <?php foreach (WebsiteHelper::otherLanguages() as $locale => $language): ?>
                    <li><a href="<?= WebsiteHelper::makeLanguageUrl($locale) ?>"><?= $locale ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <!-- Mobile menu-->
</header>
<!-- Header-->
