<?php

/**
 * @var \yii\web\View $this
 * @var \cabinet\models\PostSearch $searchModel
 * @var \cabinet\data\NewsDataProvider $dataProvider
 */

use cabinet\helpers\PostHelper;
use core\helpers\WebsiteHelper;
use yii\bootstrap4\LinkPager;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = $dataProvider->website ? $dataProvider->website->name . ' xəbərləri' : 'Bütün xəbərlər';

?>
<section class="site-news-page">
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="white-panel">
                    <div class="title-links flex">
                        <h2 class="block-title"><?= $this->title ?></h2>

                        <div class="links flex">
                            <?php if ($dataProvider->website && WebsiteHelper::rssCanBeRefreshed($dataProvider->website, $searchModel->language)): ?>
                                <a class="btn-custom force-update"
                                   id="rss-update"
                                   href="<?= Url::to(['update-rss', 'id' => $dataProvider->website->id, 'language' => $searchModel->language]) ?>"
                                >
                                    Force RSS update<i class="material-icons">refresh</i>
                                </a>
                                <p class="update-error"></p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="news-filters">
                        <form class="flex" action="<?= Url::current() ?>" onchange="this.submit()" method="GET">
                            <div class="by-day">
                                <div class="form-group select">
                                    <label>Tarix</label>
                                    <?= Html::activeDropDownList($searchModel, 'range', PostHelper::rangeList(), ['class' => 'form-control']) ?>
                                </div>
                            </div>
                            <!-- By Day-->

                            <div class="by-website">
                                <div class="form-group select">
                                    <label>Prioritet</label>
                                    <?= Html::activeDropDownList($searchModel, 'priority', PostHelper::priorityList(), [
                                        'prompt' => 'Bütün xəbərlər',
                                        'class' => 'form-control',
                                    ]) ?>
                                </div>
                            </div>
                            <!-- By Priority-->

                            <div class="search-filter hidden-991">
                                <div class="form-group">
                                    <label>Axtarış</label>
                                    <?= Html::activeTextInput($searchModel, 'title', ['class' => 'form-control']) ?>
                                </div>
                            </div>
                            <!-- Search Filter-->

                            <div class="news-lang">
                                <div class="form-group select">
                                    <label>Dil</label>
                                    <?= Html::activeDropDownList($searchModel, 'language', Yii::$app->params['languages'], ['class' => 'form-control']) ?>
                                </div>
                            </div>
                            <!-- Number To Show-->

                            <div class="num-to-show">
                                <div class="form-group select">
                                    <label>Görsətmə sayı</label>
                                    <?= Html::activeDropDownList($searchModel, 'limit', PostHelper::showCountList(), ['class' => 'form-control']) ?>
                                </div>
                            </div>
                            <!-- Number To Show-->

                            <div class="filter-by">
                                <div class="form-group select">
                                    <label>Çeşidləmə</label>
                                    <select class="form-control" name="sort" title="Çeşidləmə">
                                        <?php foreach ($dataProvider->sort->attributes as $attribute => $params): ?>
                                            <option
                                               <?= (Yii::$app->request->get('sort') == '-'.$attribute) ? 'selected' : null ?>
                                               value="-<?= $attribute ?>"><?= Yii::t('news', 'sort_' . $attribute) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- News Filters-->

                    <div class="news-list">
                        <?php
                        if (!empty($dataProvider->getModels())):
                            /** @var \core\entities\Customer\Website\Post $post */
                            foreach ($dataProvider->getModels() as $post):
                        ?>
                            <div class="site flex">
                                <div class="info flex">
                                    <div class="image hidden-575">
                                        <a href="<?= $post->link ?>" target="_blank">
                                            <img src="<?= Yii::$app->storage->post->getThumb(100, $post->image) ?>">
                                        </a>
                                    </div>

                                    <div class="name-url">
                                        <a href="<?= $post->link ?>" target="_blank">
                                            <p class="name"><?= $post->title ?></p>
                                        </a>
                                        <p class="url"><?= $post->link ?></p>
                                    </div>
                                </div>

                                <div class="actions">
                                    <div class="site-stat text-center">
                                        <p><?= $post->clicks ?></p>
                                        <p>Keçid</p>
                                    </div>

                                    <div class="site-stat text-center">
                                        <p><?= $post->views ?></p>
                                        <p>Görsənmə</p>
                                    </div>

                                    <div class="site-stat text-center">
                                        <p><?= $post->ctr ?>%</p>
                                        <p>CTR</p>
                                    </div>

                                    <?php if (!$post->prioritized()): ?>
                                        <a class="btn-dark prioritize-news" href="<?= Url::to(['prioritize', 'id' => $post->id]) ?>">
                                            <i class="material-icons">arrow_upward</i>
                                        </a>
                                    <?php endif; ?>
                                    <a class="btn-delete detele-news"
                                       href="<?= Url::to(['delete', 'id' => $post->id]) ?>"
                                       data-method="POST"
                                       data-confirm="Xəbərin silməyinizə əminsiniz?"
                                    >
                                        <i class="material-icons">highlight_off</i>
                                    </a>
                                </div>
                            </div>
                            <!-- Site-->
                        <?php endforeach; else: ?>
                            <p class="text-center">Siyahı boşdur</p>
                        <?php endif; ?>
                    </div>
                    <!-- News List-->
                    <div class="news-pagination">
                        <?= LinkPager::widget(['pagination' => $dataProvider->pagination]) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- News List Page-->
