<?php

use yii\helpers\Url;
use frontend\yii\web\View;
use core\forms\SearchForm;
use frontend\assets\AppAsset;
use frontend\helpers\WebsiteHelper;

/**
 * @var $this View
 * @var SearchForm $searchModel
 * @var string $searchQuery
 */

$this->title = $searchModel->q . ' - ' . Yii::t('main', 'search_results');
if (!empty($posts)) $this->registerPaginationTags(null, ['q' => $searchModel->q]);
$this->registerJsFile('/js/search.js?v=1.1', ['depends' => AppAsset::class]);
?>
<section class="search-page">
    <div class="news-filters">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="search-field">
                        <div class="form-group">
                            <input
                                class="search form-control"
                                value="<?= $searchQuery ?>"
                                type="text"
                                placeholder="<?= Yii::t('main', 'search') ?>">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- News filters-->

    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2 class="section-title">
                    <span><?= $this->title ?></span>
                </h2>
            </div>

            <div class="other-news-list flex">
               <?php if (!empty($posts)): ?>
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
                                       <img src="<?= WebsiteHelper::getIcon($post['website']['name']) ?>" alt="">
                                       <span><?= $post['website']['name'] ?></span>
                                   </a>
                                   <span class="date"><?= Yii::$app->formatter->asDatetime($post['created_at']) ?></span>
                               </div>
                               <a class="title" target="_blank" href="<?= Url::to(['redirect/view', 'id' => $post['id']]) ?>"><?= $post['title'] ?></a>
                           </div>
                       </div>
                   <?php endforeach; ?>
                <?php else: ?>
                   <div class="col-12 text-center">
                       <p><?= Yii::t('filters', 'no_results_found') ?></p>
                   </div>
                <?php endif; ?>
            </div>

        </div>
    </div>
</section>