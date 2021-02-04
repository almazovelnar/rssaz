<?php /** @noinspection ALL */

use core\helpers\DiagnosticsHelper;
use kartik\date\DatePicker;
use kartik\form\ActiveForm;
use yii\bootstrap4\LinkPager;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var \yii\web\View $this
 * @var \core\entities\Customer\Website\Website $website
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \cabinet\models\DiagnosticsSearch $searchModel
 */

$this->title = $website->name . ' - Diaqnostika';

$rss = $searchModel->getRss();
$postsCount = (int) $searchModel->getPostsCount();

//KVDATE I18N!!!
Yii::$app->language = 'en';

?>
<section class="diagnostics-page">
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="white-panel">
                    <div class="title-links flex">
                        <h2 class="block-title">Diaqnostika</h2>
                        <?php if (!$rss->isValid()): ?>
                            <div class="links flex">
                                <a class="btn-custom btn-restore" data-method="POST" href="<?= Url::to(['rehabilitate', 'id' => $rss->id]) ?>">
                                    Validate<i class="material-icons">cached</i>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="diagnostics-info">
                        <a href="<?= $rss->rss_address ?>" target="_blank"><?= $website->name ?></a>
                        <p><strong>RSS downloaded:</strong><?= $postsCount ?></p>
                        <p><strong>Added:</strong><?= $rss->created_at ?></p>
                    </div>

                    <!-- Diagnostics info-->

                    <div class="filters-group">
                        <?php $form = ActiveForm::begin([
                                'method' => 'GET',
                                'action' => Url::to(['index', 'id' => $website->id]),
                                'options' => ['class' => 'flex', 'onchange' => 'this.submit()']
                        ]) ?>
                            <div class="by-date">
                                <?= $form->field($searchModel, 'date')->widget(DatePicker::class, [
                                    'language' => 'en',
                                    'pickerIcon' => '<i class="material-icons">date_range</i>',
                                    'removeIcon' => '<i class="material-icons">delete</i>',
                                    'pluginOptions' => [
                                        'format' => 'yyyy-mm-dd',
                                        'language' => 'en',
                                    ]
                                ]) ?>
                            </div>
                            <!-- By Date-->

                            <div class="by-rss">
                                <?= $form->field($searchModel, 'rss_id', ['options' => ['class' => 'form-group select']])
                                    ->dropDownList(ArrayHelper::map($website->rss, 'id', 'rss_address'))->error(false) ?>
                            </div>

                            <div class="by-status">
                                <?= $form->field($searchModel, 'status', ['options' => ['class' => 'form-group select']])
                                    ->dropDownList(array_merge(['' => ''], DiagnosticsHelper::statusesList()))->error(false) ?>
                            </div>
                            <!-- By RSS-->
                        <?php ActiveForm::end() ?>
                    </div>
                    <!-- Filters Group-->

                    <div class="diagnostics-content">
                        <div class="diagnostics-table x6-cell">
                            <div class="t-row flex">
                                <div class="t-cell">Time</div>
                                <div class="t-cell">Status</div>
                                <div class="t-cell">Success</div>
                                <div class="t-cell">Warnings</div>
                                <div class="t-cell">Errors</div>
                                <div class="t-cell">Fatal</div>
                            </div>
                            <?php
                            /** @var \core\entities\Parse\Parse $parse */
                            foreach ($dataProvider->getModels() as $i => $parse):
                            ?>
                            <div class="t-row">
                                <div class="flex <?= $parse->isSuccessful() ? 'no-errors' : '' ?>" data-toggle="collapse" data-target="#v-result<?= $i ?>" aria-expanded="false">
                                    <div class="t-cell">
                                        <p class="time"><?= date('H:i', strtotime($parse->created_at)) ?></p>
                                    </div>
                                    <div class="t-cell">
                                        <p class="status"><?= DiagnosticsHelper::statusLabel($parse->status) ?></p>
                                    </div>
                                    <div class="t-cell">
                                        <span><?= $parse->news_count ?></span>
                                    </div>
                                    <div class="t-cell">
                                        <span><?= $parse->warnings_count ?></span>
                                    </div>
                                    <div class="t-cell">
                                        <span><?= $parse->errors_count ?></span>
                                    </div>
                                    <div class="t-cell">
                                        <span><?= $parse->fatals_count ?></span>
                                    </div>
                                </div>
                                <?php if (!empty($parse->parseErrors)): ?>
                                    <div class="collapse" id="v-result<?= $i ?>">
                                        <div class="v-response">
                                            <p class="v-message">Errors were found during the RSS update. Some pages may not be displayed or displayed incorrectly.</p>
                                            <?php if ($parse->rss_filename): ?>
                                                <a href="<?= Yii::getAlias('@web/brokenRss/' . $parse->rss_filename) ?>" class="text-danger" download>Broken rss file</a>
                                            <?php endif; ?>
                                            <div class="v-info">
                                                <strong>Last file validation</strong>
                                                <p>Total pages: <?= $parse->getTotal() ?></p>
                                                <p>Correct ones: <?= $parse->news_count ?></p>
                                                <p>Warnings: <?= $parse->warnings_count ?></p>
                                                <p>Errors: <?= $parse->errors_count ?></p>
                                                <p>Fatal errors: <?= $parse->fatals_count ?></p>
                                            </div>
                                            <!-- Validation info-->
                                            <div class="detailed-response">
                                                <div class="errors-counter">
                                                    <div <?php if ($parse->warnings_count) { ?>class="active"<?php } ?>>
                                                        <?= $parse->warnings_count == 0 ? 'no warnings' : $parse->warnings_count . ' warning(s)' ?>
                                                    </div>
                                                    <div <?php if ($parse->errors_count) { ?>class="active"<?php } ?>>
                                                        <?= $parse->errors_count == 0 ? 'no errors' : $parse->errors_count . ' error(s)' ?>
                                                    </div>
                                                    <div <?php if ($parse->fatals_count) { ?>class="active"<?php } ?>>
                                                        <?= $parse->fatals_count == 0 ? 'no fatals' : $parse->fatals_count . ' fatal(s)' ?>
                                                    </div>
                                                </div>
                                                <?php foreach ($parse->parseErrors as $error): ?>
                                                <div class="response-code">
                                                    <p><?= $error->message ?></p>
                                                    <?php if ($error->hasLineAndPos()): ?>
                                                        <div class="code-chunk">
                                                            <div class="coords flex">
                                                                <span>line</span>
                                                                <span>pos</span>
                                                            </div>
                                                            <div class="code flex">
                                                                <span><?= $error->line ?></span>
                                                                <span><?= $error->column ?></span>
                                                                <div><span class="highlight"><?= Html::encode($error->fragment) ?></span></div>
                                                            </div>
                                                        </div>
                                                    <?php else: ?>
                                                        <div class="code-highlight"><?= $error->fragment ?></div>
                                                    <?php endif; ?>
                                                </div>
                                                <?php endforeach; ?>
                                            </div>
                                            <!-- Detailed Reponse-->
                                        </div>
                                    </div>
                                    <!-- Collapse-->
                                <?php endif; ?>
                            </div>
                            <!-- T-row-->
                            <?php endforeach; ?>
                        </div>
                        <!-- Diagnostics table-->
                        <div class="news-pagination">
                            <?= LinkPager::widget(['pagination' => $dataProvider->pagination]) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
