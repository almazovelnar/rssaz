<?php foreach ($data as $item): ?>
    <div class="slide">
        <div class="slide-post" style="background-color: #f4f4f4">
            <div
                class="slide-post__image"
                style="background-image: url(<?= Yii::$app->storage->post->getThumb(375, $item->image) ?>)"></div>
            <div
                class="gradient"
                style="background-image: linear-gradient(to-bottom, rgba(0, 0, 0, 0), rgba(0, 0, 0, .8), rgba(0, 0, 0, 1), rgba(0, 0, 0, 1))"></div>
            <a class="slide-post__info" href="javascript:void(0)">
                <span style="color: #fff"><?= $item->title ?></span>
            </a>
        </div>
    </div>
<?php endforeach; ?>
