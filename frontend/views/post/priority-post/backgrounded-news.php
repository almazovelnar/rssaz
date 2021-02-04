<?php

use frontend\helpers\WebsiteHelper;
use yii\helpers\{Url, StringHelper};

/**
 * @var \core\entities\Customer\Website\Post $post
 */
?>
<div class="backgrounded-news">
    <div class="image lazy">
        <a href="<?= Url::to(['redirect/view', 'id' => $post['id']]) ?>" target="_blank">
            <img data-src="<?= Yii::$app->storage->post->getThumb(575, $post['image']) ?>" alt="<?= $post['title'] ?>">
        </a>
    </div>
    <div class="info">
        <div class="source-title flex">
            <a class="source" href="<?= $post['address'] ?>" target="_blank">
                <img src="<?= WebsiteHelper::getIcon($post['name']) ?>" alt="<?= $post['name'] ?>">
                <span><?= $post['name'] ?></span>
            </a>
            <a class="title" href="<?= Url::to(['redirect/view', 'id' => $post['id']]) ?>" target="_blank"><?= $post['title'] ?></a>
        </div>
        <p class="description"><?= StringHelper::truncate($post['description'], 140) ?></p>
        <span class="date"><?= Yii::$app->formatter->asDatetime($post['created_at']) ?></span>
    </div>
</div>