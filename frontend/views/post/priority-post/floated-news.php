<?php

use frontend\helpers\WebsiteHelper;
use yii\helpers\StringHelper;
use yii\helpers\Url;

/**
 * @var \core\entities\Customer\Website\Post $post
 */
?>
<div class="floated-news flex">
    <div class="image lazy">
        <a href="<?= Url::to(['redirect/view', 'id' => $post['id']]) ?>" target="_blank">
            <img data-src="<?= Yii::$app->storage->post->getThumb(275, $post['image']) ?>" alt="<?= $post['title'] ?>">
        </a>
    </div>
    <div class="info">
        <a class="title" href="<?= Url::to(['redirect/view', 'id' => $post['id']]) ?>" target="_blank">
            <img src="<?= WebsiteHelper::getIcon($post['name']) ?>" alt="<?= $post['name'] ?>">
            <?= $post['title'] ?>
        </a>

        <p class="description"><?= StringHelper::truncate($post['description'], 70) ?></p>
        <span class="date"><?= Yii::$app->formatter->asDatetime($post['created_at']) ?></span>
    </div>
</div>