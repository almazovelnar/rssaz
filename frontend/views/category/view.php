<?php
/** @var \core\entities\Category\Category $category */
/** @var \core\entities\Customer\Website\Post[] $posts */
/** @var $this \frontend\yii\web\View */

use frontend\helpers\WebsiteHelper;
use yii\helpers\Url;
$this->title = Yii::t('main', 'news') . ' - ' . $category->multilingual->meta->title;

$this->registerMetaTag(['name' => 'keywords', 'description' => $category->multilingual->meta->keywords]);
$this->registerMetaTag(['name' => 'description', 'description' => $category->multilingual->meta->description]);

if (!empty($posts)) {
    $this->registerPaginationTags(null, ['slug' => $category->slug]);
}

$this->registerJsFile('/js/search.js?v=1.1', ['depends' => \frontend\assets\AppAsset::class]);
?>
<section class="category-page">

    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1 class="section-title">
                    <span><?= $this->title ?></span>
                </h1>
            </div>

            <?php if (!empty($posts)): ?>
                <div class="other-news-list flex load-container" data-url="<?= Url::to(['category/list']) ?>">
                    <?php foreach ($posts as $post): ?>
                        <div class="news-block othernews-block newsBlock" data-timestamp="<?= $post['parsed_at'] ?>" data-category="<?= $post['category_id'] ?>">
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
            <?php else: ?>
                <p><?= Yii::t('main','empty_list')?></p>
            <?php endif; ?>

        </div>
    </div>
</section>