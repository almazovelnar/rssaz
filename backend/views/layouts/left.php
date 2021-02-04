<?php

/** @var \core\entities\User $user */

use backend\widgets\Menu;
use yii\helpers\{Url, Html};

$user = Yii::$app->user->identity;
?>

<div class="site-menubar">
    <div class="site-menubar-body">
        <div>
            <div>
                <?= Menu::widget(
                    [
                        'items' => [
                            ['label' => 'Menu', 'options' => ['class' => 'site-menu-category']],
                            ['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],
                            ['label' => 'Dashboard', 'icon' => 'view-dashboard', 'url' => ['/']],
                            ['label' => 'Pages', 'icon' => 'collection-item', 'url' => ['/page/default/index']],
                            ['label' => 'Categories', 'icon' => 'format-list-bulleted', 'url' => ['/category/default/index']],

                            [
                                'label' => 'Customers',
                                'icon' => 'accounts',
                                'url' => '#',
                                'items' => [
                                    ['label' => 'Customers', 'icon' => 'accounts', 'url' => ['/customer/default/index']],
                                    ['label' => 'Reviews', 'icon' => 'comments', 'url' => ['/customer/reviews/index']],
                                ]
                            ],
                            [
                                'label' => 'Websites',
                                'icon' => 'globe',
                                'url' => '#',
                                'items' => [
                                    ['label' => 'List', 'icon' => 'filter-list', 'url' => ['/website/default/index']],
                                    ['label' => 'News', 'icon' => 'comment-text-alt', 'url' => ['/website/news/index']],
                                    ['label' => 'Stats', 'icon' => 'equalizer', 'url' => ['/website/stats/index']],
                                    ['label' => 'Removed News', 'icon' => 'comment-edit', 'url' => ['/website/removed-news/index'], 'visible' => Yii::$app->user->identity->isAdmin()],
                                    ['label' => 'Duplicated News', 'icon' => 'alert-triangle', 'url' => ['/website/duplicated-news/index'], 'visible' => Yii::$app->user->identity->isAdmin()],
                                ]
                            ],
                            ['label' => 'Diagnostics', 'icon' => 'settings', 'url' => ['/diagnostics/default/index']],
                            ['label' => 'Statistics', 'icon' => 'chart', 'url' => ['/statistics/default/index']],
                            [
                                'label' => 'Anti-fraud',
                                'icon'  => 'shield-security',
                                'url'   => '#',
                                'items' => [
                                    ['label' => 'TOP IP', 'icon' => 'user', 'url' => ['/antifraud/ip/index']],
                                    ['label' => 'TOP AGENT', 'icon' => 'user', 'url' => ['/antifraud/agent/index']],
                                    ['label' => 'All', 'icon' => 'user', 'url' => ['/antifraud/default/index']],
                                ],
                            ],
                            ['label' => 'Users', 'icon' => 'account', 'url' => ['/user/default/index'], 'visible' => Yii::$app->user->identity->isAdmin()],
                            ['label' => 'Configuration', 'icon' => 'settings-square', 'url' => ['/config/default/index']],
                        ],
                    ]
                ) ?>
            </div>
        </div>
    </div>

    <div class="site-menubar-footer">
        <a href="javascript: void(0);" class="fold-show" data-toggle="menubar" role="button">
            <i class="icon hamburger hamburger-arrow-left">
                <span class="sr-only">Toggle menubar</span>
                <span class="hamburger-bar"></span>
            </i>
        </a>
        <a class="nav-link navbar-avatar" data-toggle="dropdown" href="#" aria-expanded="false"
            role="button">
            <span class="avatar avatar-online">
              <img src="<?= Yii::getAlias('@web/images/user.png') ?>" alt="<?= $user->username ?>">
              <i></i>
            </span>
        </a>
        <div class="dropdown-menu" role="menu">
            <a class="dropdown-item" href="<?= Url::toRoute(['/user/default/view', 'id' => $user->id]) ?>" role="menuitem"><i class="icon md-account" aria-hidden="true"></i> Profile</a>
            <div class="dropdown-divider" role="presentation"></div>
            <?= Html::a(
                '<i class="icon md-power" aria-hidden="true"></i> Sign out',
                ['/site/logout'],
                ['data-method' => 'post', 'class' => 'dropdown-item', 'role' => 'menuitem']
            ) ?>
        </div>
        <?= Html::a(
            '<i class="icon md-power" aria-hidden="true"></i>',
            ['/site/logout','data' => ['confirm' => 'adawd']],
            ['data-method' => 'post', 'data-original-title' => 'Sign out', 'data-placement' => 'top', 'data-toggle' => 'tooltip', 'data-confirm' => 'Are you sure you want to logout?']
        ) ?>
    </div>
</div>
