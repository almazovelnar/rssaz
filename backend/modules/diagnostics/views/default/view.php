<?php

use yii\helpers\Html;
use yii\widgets\LinkPager;
use core\entities\Parse\Parse;
use core\helpers\DiagnosticsHelper;
use core\entities\Customer\Website\{Website, Rss};

/** @var Rss $rss */
/** @var Parse $parse */
/** @var Website $website */
/** @var \frontend\yii\web\View $this */
/** @var \yii\data\ArrayDataProvider $dataProvider */

$this->title = 'Diagnostics info - ' . $website->name;
$this->registerCss(".error { color:red }");
?>

<div class="diagnostics-info">
    <div class="box">
        <div class="box-header with-border">
            <h4>Rss: <a href="<?= $rss->rss_address ?>" target="_blank"><?= $rss->rss_address ?></a></h4>
            <p><?= DiagnosticsHelper::statusLabel($parse->status)?></p>
            <dl class="dl-horizontal">
                <dt>Total pages</dt>
                <dd><?= $parse->getTotal() ?></dd>

                <dt>Correct ones</dt>
                <dd><?= $parse->news_count ?></dd>

                <dt>Warnings</dt>
                <dd><?= $parse->warnings_count ?></dd>

                <dt>Errors</dt>
                <dd><?= $parse->errors_count ?></dd>

                <dt>Fatal errors</dt>
                <dd><?= $parse->fatals_count ?></dd>
                <?php if ($parse->status != LIBXML_ERR_NONE): ?>
                    <?php if ($parse->rss_filename): ?>
                         <dt>Broken rss</dt>
                         <dd>
                             <a target="_blank" href="<?= Yii::$app->cabinetUrlManager->getHostInfo() ?>/brokenRss/<?= $parse->rss_filename ?>"
                                download class="text-danger">Broken rss file</a>
                         </dd>
                    <?php endif; ?>
                <?php else: ?>
                    <dt>Elapsed time</dt>
                    <dd><?= $parse->elapsed_time ?>s</dd>
                <?php endif; ?>
            </dl>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-md-10 col-md-offset-1">


                    <div class="box-group" id="accordion">
                        <?php foreach ($dataProvider->getModels() as $key => $error): ?>
                            <div class="panel box box-danger">
                                <div class="box-header with-border">
                                    <h4 class="box-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse<?= $key ?>" class="collapsed" aria-expanded="false">
                                            <?= $error->message ?>
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapse<?= $key ?>" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                                    <div class="box-body">
                                        <?php if ($error->hasLineAndPos()): ?>
                                            <dl class="dl-horizontal">
                                                <dt>Line</dt>
                                                <dd><?= $error->line ?></dd>

                                                <dt>Position: </dt>
                                                <dd><?= $error->column ?></dd>
                                            </dl>
                                            <?= Html::encode($error->fragment) ?>
                                        <?php else: ?>
                                            <?= $error->fragment ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <br>
                        <?php endforeach; ?>
                    </div>

                    <div class="news-pagination">
                        <?= LinkPager::widget(['pagination' => $dataProvider->pagination]) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>