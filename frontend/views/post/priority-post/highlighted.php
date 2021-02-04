<?php

use frontend\helpers\WebsiteHelper;
use yii\helpers\StringHelper;
use yii\helpers\Url;

/**
 * @var \core\entities\Customer\Website\Post $post
 */
?>
<div class="inline-news highlighted">
    <a class="title" href="<?= Url::to(['redirect/view', 'id' => $post['id']]) ?>" target="_blank">
        <img src="<?= WebsiteHelper::getIcon($post['name']) ?>" alt="<?= $post['name'] ?>">
        <?= $post['title'] ?>
    </a>
    <p class="description"><?= StringHelper::truncate($post['description'], 140) ?></p>
    <span class="date"><?= Yii::$app->formatter->asDatetime($post['created_at']) ?></span>
</div>