<?php

/** @var array $posts */
use yii\helpers\Url;
use frontend\helpers\WebsiteHelper;
?>
<?php foreach ($posts as $post): ?>
    <div class="news-block othernews-block">
        <div class="image lazy">
            <a href="<?= Url::to(['redirect/view', 'id' => $post['id']]) ?>" target="_blank">
                <img src="<?= Yii::$app->storage->post->getThumb(375, $post['image']) ?>" alt="<?= $post['title'] ?>">
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
