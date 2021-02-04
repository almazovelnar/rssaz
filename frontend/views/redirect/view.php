<?php

use yii\web\View;
use frontend\assets\AppAsset;
use frontend\widgets\Actual\Actual;
use frontend\helpers\WebsiteHelper;
use yii\helpers\{Url, StringHelper};
use core\entities\Customer\Website\Post;
use frontend\widgets\OtherNews\OtherNews;
use frontend\widgets\Categories\Categories;

/**
 * @var View $this
 * @var Post $post
 * @var Post $nextPost
 * @var Post[] $posts
 * @var Post[] $similarPosts
 * @var Post[] $otherPosts
 *
 * @var string $sid
 */
$this->title = $post->getTitle();
$this->registerJsFile('/js/main.js?v=1.1', ['depends' => AppAsset::class]);
?>
<section class="redirect-page" data-next="<?= Url::to(['/redirect/' . $nextPost['id'], 'sid' => $sid]) ?>">
    <div class="container ptb-3">
        <div class="row">
            <div class="col-md-6 selected-news-wrapper">
                <div class="news-block selected">
                    <div class="image lazy">
                        <a href="<?= $post->getLink() ?>" class="read-post" target="_blank">
                            <img data-src="<?= Yii::$app->storage->post->getThumb(375, $post->image) ?>" alt="<?= $post->title ?>">
                        </a>
                    </div>
                    <div class="info">
                        <div class="source-date flex">
                            <a class="source" href="<?= $post->website->getAddress() ?>" target="_blank">
                                <img src="<?= WebsiteHelper::getIcon($post->website->getName()) ?>">
                                <span><?= $post->website->getName() ?></span>
                            </a>
                            <span class="date"><?= Yii::$app->formatter->asDatetime($post->created_at) ?></span>
                        </div>
                        <p class="title"><a href="<?= $post->getLink() ?>" target="_blank"  class="read-post"><?= $post->title ?></a></p>
                        <p class="description"><?= StringHelper::truncate($post->description, 100) ?></p>
                    </div>

                    <div class="see-more read-post">
                        <a href="<?= $post->getLink() ?>" target="_blank"><?= Yii::t('main', 'read_more') ?>
                            <i class="icon-angle-right"></i>
                        </a>
                    </div>
                </div>
                <!-- Selected-news-->

                <div class="row hidden-767">
                    <?php foreach (array_splice($similarPosts, 0, 4) as $similarPost): ?>
                        <div class="col-md-6">
                            <div class="news-block">
                                <div class="image lazy">
                                    <a href="<?= Url::to(['redirect/view', 'id' => $similarPost['id']]) ?>" target="_blank">
                                        <img data-src="<?= Yii::$app->storage->post->getThumb(375, $similarPost['image']) ?>" alt="<?= $similarPost['title'] ?>">
                                    </a>
                                </div>
                                <div class="info">
                                    <div class="source-date flex">
                                        <a class="source" href="<?= $similarPost['address'] ?>" target="_blank">
                                            <img src="<?= WebsiteHelper::getIcon($similarPost['name']) ?>">
                                            <span><?= $similarPost['name'] ?></span>
                                        </a>
                                        <span class="date"><?= Yii::$app->formatter->asDatetime($similarPost['created_at']) ?></span>
                                    </div>
                                    <a class="title" href="<?= Url::to(['redirect/view', 'id' => $similarPost['id']]) ?>" target="_blank"><?= $similarPost['title'] ?></a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <!-- Col (Selected news)-->

            <div class="col-md-3 similar-news-wrapper">
                <h2 class="section-title">
                    <span><?= Yii::t('main', 'similar_news') ?></span>
                </h2>

                <?php foreach ($similarPosts as $index => $similarPost): ?>
                    <?php if ($index == 0): ?>
                        <div class="news-block">
                            <div class="image lazy">
                                <a href="<?= Url::to(['redirect/view', 'id' => $similarPost['id']]) ?>" target="_blank">
                                    <img data-src="<?= Yii::$app->storage->post->getThumb(375, $similarPost['image']) ?>" alt="<?= $similarPost['title'] ?>">
                                </a>
                            </div>
                            <div class="info">
                                <div class="source-date flex">
                                    <a class="source" href="<?= $similarPost['address'] ?>" target="_blank">
                                        <img src="<?= WebsiteHelper::getIcon($similarPost['name']) ?>">
                                        <span><?= $similarPost['name'] ?></span>
                                    </a>
                                    <span class="date"><?= Yii::$app->formatter->asDatetime($similarPost['created_at']) ?></span>
                                </div>
                                <a class="title" href="<?= Url::to(['redirect/view', 'id' => $similarPost['id']]) ?>" target="_blank"><?= $similarPost['title'] ?></a>
                                <div class="description">
                                    <p><?= StringHelper::truncate($similarPost['description'], 100) ?></p>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="news-block">
                            <div class="info">
                                <div class="source-date flex">
                                    <a class="source" href="<?= $similarPost['address'] ?>" target="_blank">
                                        <img src="<?= WebsiteHelper::getIcon($similarPost['name']) ?>">
                                        <span><?= $similarPost['name'] ?></span>
                                    </a>
                                    <span class="date"><?= Yii::$app->formatter->asDatetime($similarPost['created_at']) ?></span>
                                </div>
                                <a class="title" target="_blank" href="<?= Url::to(['redirect/view', 'id' => $similarPost['id']]) ?>"><?= $similarPost['title'] ?></a>
                                <div class="description">
                                    <p><?= StringHelper::truncate($similarPost['description'], 100) ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>

            </div>
            <!-- Col (Related news)-->

            <div class="col-lg-3 col-md-6 news-feed-wrapper">
                <h2 class="section-title">
                    <span><?= Yii::t('main', 'latest_news') ?></span>
                </h2>

                <div class="news-list">
                   <?php foreach ($posts as $post): ?>
                       <?php if ($post['priority'] > 0): ?>
                           <?php
                           $priorityType = array_shift($priorityPostTypes);
                           echo $this->render('/post/priority-post/' . $priorityType, ['post' => $post]);
                           $priorityPostTypes[] = $priorityType;
                           ?>
                       <?php else: ?>
                               <div class="inline-news">
                                   <a class="title" href="<?= Url::to(['redirect/view', 'id' => $post['id']]) ?>" target="_blank">
                                       <img src="<?= WebsiteHelper::getIcon($post['name']) ?>" alt="<?= $post['title'] ?>">
                                       <?= $post['title'] ?>
                                   </a>
                                   <span class="date"><?= Yii::$app->formatter->asDatetime($post['created_at']) ?></span>
                               </div>
                       <?php endif; ?>
                    <?php endforeach; ?>
                </div>
                <!-- News list-->

                <div class="see-all">
                    <a href="<?= Url::to(['post/index']) ?>"><?= Yii::t('main', 'all_news') ?>
                        <i class="icon-angle-right"></i>
                    </a>
                </div>
                <!-- See all news-->
            </div>
            <!-- Col (News feed)-->
        </div>
    </div>
    <!-- Redirect news container-->

    <?= Actual::widget(['posts' => $otherPosts]) ?>
    <?= Categories::widget() ?>
    <?= OtherNews::widget(['posts' => $otherPosts]) ?>

</section>
