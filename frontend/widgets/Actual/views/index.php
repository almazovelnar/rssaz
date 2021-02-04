<?php

/** @var \core\entities\Customer\Website\Post[] $posts */
use yii\helpers\Url;
use frontend\helpers\WebsiteHelper;
?>
<div class="actual-news ptb-5">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2 class="section-title">
                    <span><?= Yii::t('main', 'actual') ?></span>
                </h2>
            </div>
            <!-- Col-->

            <?php foreach ($posts as $post): ?>
            <div class="col-md-3 col-sm-6">
                <div class="news-block">
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
            </div>
            <?php endforeach; ?>

            <!-- Col-->
        </div>
    </div>
</div>