<?php

use yii\web\View;
use frontend\assets\AppAsset;
use frontend\helpers\WebsiteHelper;
use yii\helpers\{Url, StringHelper};
use core\entities\Customer\Website\Post;
use frontend\widgets\{Actual\Actual, Categories\Categories};

/** @var View $this */
/** @var string $defaultPeriod */
/** @var Post[] $otherPosts */
/** @var Post[] $actualPosts */

$firstPost = array_shift($posts);
$this->title = Yii::$app->name;
$this->registerJsFile('/js/filters.js', ['depends' => AppAsset::class]);
?>
<section class="main-page">
    <?= $this->render('partials/news-filters', ['defaultPeriod' => $defaultPeriod]) ?>

    <div class="container pb-5">
        <div class="row">
            <div class="col-md-6 hot-news-wrapper hidden-767">
                <h2 class="section-title">
                    <span><?= Yii::t('main', 'hot_news') ?></span>
                </h2>
                <?php if ($firstPost): ?>
                    <div class="news-block big">
                        <div class="image lazy">
                            <a href="<?= Url::to(['redirect/view', 'id' => $firstPost['id']]) ?>" target="_blank">
                                <img data-src="<?= Yii::$app->storage->post->getThumb(575, $firstPost['image']) ?>" alt="<?= $firstPost['title'] ?>">
                            </a>
                        </div>
                        <div class="info">
                            <div class="source-date flex">
                                <a class="source" href="<?= $firstPost['address'] ?>" target="_blank">
                                    <img src="<?= WebsiteHelper::getIcon($firstPost['name']) ?>">
                                    <span><?= $firstPost['name'] ?></span>
                                </a>
                                <span class="date"><?= Yii::$app->formatter->asDatetime($firstPost['created_at']) ?></span>
                            </div>
                            <a class="title" target="_blank" href="<?= Url::to(['redirect/view', 'id' => $firstPost['id']]) ?>"><?= $firstPost['title'] ?></a>
                            <p class="description"><?= StringHelper::truncate($firstPost['description'], 100) ?></p>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="row">
                    <?php foreach (array_splice($posts, 0, 4) as $post): ?>
                        <div class="col-md-6">
                            <div class="news-block">
                                <div class="image lazy">
                                    <a target="_blank" href="<?= Url::to(['redirect/view', 'id' => $post['id']]) ?>">
                                        <img data-src="<?= Yii::$app->storage->post->getThumb(375, $post['image']) ?>" alt="<?= $post['title'] ?>">
                                    </a>
                                </div>
                                <div class="info">
                                    <div class="source-date flex">
                                        <a class="source" href="<?= $post['address'] ?>" target="_blank">
                                            <img src="<?= WebsiteHelper::getIcon($post['name']) ?>">
                                            <span><?= $post['name'] ?></span>
                                        </a>
                                        <span class="date"><?= Yii::$app->formatter->asDatetime($post['created_at']) ?></span>
                                    </div>
                                    <a target="_blank" class="title" href="<?= Url::to(['redirect/view', 'id' => $post['id']]) ?>"><?= $post['title'] ?></a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Col (Hot news)-->

            <div class="col-md-6 news-feed-wrapper">
                <h2 class="section-title">
                    <span><?= Yii::t('main', 'news_feed') ?></span>
                </h2>

                <div class="news-list">
                    <?php
                        $i = 0;
                        foreach ($posts as $post):
                            $highlighted = '';
                    ?>
                        <?php if ($post['priority'] > 0): ?>
                            <?php
                                $priorityType = array_shift($priorityPostTypes);
                                echo $this->render('/post/priority-post/' . $priorityType, ['post' => $post]);
                                $priorityPostTypes[] = $priorityType;
                            ?>
                        <?php else: ?>
                            <?php if ($i < 3 && ($highlighted = rand(0, 1)) != 0):
                                $i++;
                                $highlightedType = array_shift($highlightedTypes);
                                echo $this->render('/post/priority-post/' . $highlightedType, ['post' => $post]);
                            ?>
                            <?php else: ?>
                                <div class="inline-news">
                                    <a class="title" target="_blank" href="<?= Url::to(['redirect/view', 'id' => $post['id']]) ?>">
                                        <img src="<?= WebsiteHelper::getIcon($post['name']) ?>" alt="<?= $post['name'] ?>">
                                        <?= $post['title'] ?>
                                    </a>
                                    <p class="description"><?= StringHelper::truncate($post['description'], 140) ?></p>
                                    <span class="date"><?= Yii::$app->formatter->asDatetime($post['created_at']) ?></span>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
                <!-- News list-->

                <div class="see-all">
                    <a href="<?= Url::to(['post/index']) ?>"><?= Yii::t('main', 'all_news') ?><i class="icon-angle-right"></i></a>
                </div>
                <!-- See all news-->
            </div>
        </div>
    </div>

    <?= Actual::widget(['posts' => $actualPosts]) ?>
    <?= Categories::widget(); ?>

    <div class="other-news pt-5 hidden-767">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h2 class="section-title">
                        <span><?= Yii::t('main', 'other_news') ?></span>
                    </h2>
                </div>
                <!-- Col-->
                <div class="other-news-list flex" data-url="<?= Url::to(['post/list']) ?>">
                    <?php foreach ($otherPosts as $post): ?>
                        <div class="news-block othernews-block" data-timestamp="<?= $post['created_at'] ?>">
                            <div class="image lazy">
                                <a target="_blank" href="<?= Url::to(['redirect/view', 'id' => $post['id']]) ?>">
                                    <img data-src="<?= Yii::$app->storage->post->getThumb(375, $post['image']) ?>" alt="<?= $post['title'] ?>">
                                </a>
                            </div>
                            <div class="info">
                                <div class="source-date flex">
                                    <a class="source" href="<?= $post['website']['address'] ?>" target="_blank">
                                        <img src="<?= WebsiteHelper::getIcon($post['website']['name']) ?>">
                                        <span><?= $post['website']['name'] ?></span>
                                    </a>
                                    <span class="date"><?= Yii::$app->formatter->asDatetime($post['created_at']) ?></span>
                                </div>
                                <a class="title" target="_blank" href="<?= Url::to(['redirect/view', 'id' => $post['id']]) ?>"><?= $post['title'] ?></a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>
