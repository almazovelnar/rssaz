<?php

/** @var array $columns */
/** @var core\entities\Category\Category $category */

use yii\helpers\Url;
use frontend\helpers\WebsiteHelper;
?>
<div class="other-category-news pt-5">
    <div class="container">
        <div class="row">

            <?php foreach ($columns as $categories): ?>
                <div class="col-md-3 col-sm-6">
                    <?php foreach ($categories as $category): ?>
                        <div class="category-block">
                            <h2 class="section-title"><span><?= $category->multilingual->title ?></span></h2>
                            <?php if (isset($posts[$category->id]) && !empty($posts[$category->id])): ?>
                                   <?php foreach ($posts[$category->id] as $post): ?>
                                    <div class="news-block">
                                        <div class="image lazy">
                                            <a href="<?= Url::to(['redirect/view', 'id' => $post['id']]) ?>" target="_blank">
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
                                            <a class="title" target="_blank" href="<?= Url::to(['redirect/view', 'id' => $post['id']]) ?>"><?= $post['title'] ?></a>
                                        </div>
                                    </div>
                                   <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>

        </div>
    </div>
</div>