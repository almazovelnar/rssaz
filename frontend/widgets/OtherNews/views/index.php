<?php

/** @var array $posts */

use yii\helpers\Url;
use frontend\helpers\WebsiteHelper;
?>
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
                <?php foreach ($posts as $post): ?>
                    <div class="news-block othernews-block newsBlock" data-timestamp="<?= $post['created_at'] ?>">
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