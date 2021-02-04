<?php

/** @var Post[] $posts */
/** @var $this View */

use yii\helpers\Url;
use frontend\yii\web\View;
use frontend\assets\AppAsset;
use frontend\helpers\WebsiteHelper;
use core\entities\Customer\Website\Post;

$this->title = Yii::t("main",'latest_news');
$this->registerJsFile('/js/search.js?v=1.1', ['depends' => AppAsset::class]);
if (!empty($posts)) $this->registerPaginationTags();
?>

<section class="category-page">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1 class="section-title">
                    <span><?= $this->title ?></span>
                </h1>
            </div>

            <div class="other-news-list flex">
                <?php foreach ($posts as $post): ?>
                    <div class="news-block othernews-block newsBlock" data-timestamp="<?= $post['parsed_at'] ?>">
                        <div class="image lazy">
                            <a href="<?= Url::to(['redirect/view', 'id' => $post['id']]) ?>" target="_blank">
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
</section>