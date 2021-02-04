<?php

use core\helpers\WebsiteHelper;
use kartik\select2\Select2;
use kartik\touchspin\TouchSpin;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model \core\forms\manager\Website\CreateForm */

$this->title = 'Create Website';
$this->params['breadcrumbs'][] = ['label' => 'Websites', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="website-create">
    <div class="website-form">
        <?php $form = ActiveForm::begin() ?>
            <div class="row">
                <div class="col-lg-9">
                    <div class="box box-primary">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                                </div>
                                <div class="col-lg-6">
                                    <?= $form->field($model, 'address')->textInput(['maxlength' => true, 'placeholder' => 'http://example.com']) ?>
                                </div>
                            </div>
                            <p class="text-danger"><?= $model->getFirstError('requiredRssAddress') ?></p>
                            <div class="nav-tabs-custom">
                                <ul class="nav nav-tabs">
                                    <?php foreach (Yii::$app->params['languages'] as $code => $label): ?>
                                        <li class="nav-item">
                                            <a href="#<?= $code ?>" data-toggle="tab" class="nav-link <?= $code == Yii::$app->params['defaultLanguage'] ? 'active' : '' ?>"><?= $label ?></a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                                <div class="tab-content">
                                    <?php foreach ($model->rss as $rssForm): ?>
                                        <div class="tab-pane <?= $rssForm->language == Yii::$app->params['defaultLanguage'] ? 'active' : '' ?>" id="<?= $rssForm->language ?>">
                                            <?= $form->field($rssForm, 'rssAddress')
                                                ->textInput(['maxlength' => true, 'placeholder' => "http://example.com/{$rssForm->language}/rss.xml"]) ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <hr>
                            <?= $form->field($model, 'blockedDomains')->widget(Select2::class, [
                                'options' => ['multiple' => true, 'placeholder' => 'Type domain...'],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                    'minimumInputLength' => 3,
                                    'language' => [
                                        'errorLoading' => new JsExpression("function () { return 'Searching...'; }"),
                                    ],
                                    'ajax' => [
                                        'url' => Url::to(['domains']),
                                        'dataType' => 'json',
                                        'data' => new JsExpression('function(params) { return {q:params.term}; }')
                                    ],
                                    'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                                    'templateResult' => new JsExpression('function(domain) { return domain.text; }'),
                                    'templateSelection' => new JsExpression('function (domain) { return domain.text; }'),
                                ],
                            ]) ?>

                            <?= $form->field($model, 'algorithms')->widget(Select2::class, [
                                'data' => $model->getAvailableAlgorithms(),
                                'showToggleAll' => false,
                                'theme' => Select2::THEME_KRAJEE_BS4,
                                'options' => [
                                    'placeholder' => 'Sayt üçün alqoritm seçin',
                                    'multiple' => true,
                                    'class' => 'form-group select',
                                ],
                                'pluginOptions' => ['allowClear' => true],
                            ]);
                            ?>

                            <?= $form->field($model, 'whiteListedDomains')->widget(Select2::class, [
                                'options' => ['multiple' => true, 'placeholder' => 'Type domain...'],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                    'minimumInputLength' => 3,
                                    'language' => [
                                        'errorLoading' => new JsExpression("function () { return 'Searching...'; }"),
                                    ],
                                    'ajax' => [
                                        'url' => Url::to(['domains']),
                                        'dataType' => 'json',
                                        'data' => new JsExpression('function(params) { return {q:params.term}; }')
                                    ],
                                    'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                                    'templateResult' => new JsExpression('function(domain) { return domain.text; }'),
                                    'templateSelection' => new JsExpression('function (domain) { return domain.text; }'),
                                ],
                            ]) ?>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="box box-warning">
                        <div class="box-body">
                            <?= $form->field($model, 'customerId')->widget(Select2::class, [
                                'options' => ['placeholder' => 'Type name...'],
                                'pluginOptions' => [
                                    'minimumInputLength' => 3,
                                    'language' => [
                                        'errorLoading' => new JsExpression("function () { return 'Searching...'; }"),
                                    ],
                                    'ajax' => [
                                        'url' => Url::to(['/customer/default/list']),
                                        'dataType' => 'json',
                                        'data' => new JsExpression('function(params) { return {q:params.term}; }')
                                    ],
                                    'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                                    'templateResult' => new JsExpression('function(customer) { return customer.text; }'),
                                    'templateSelection' => new JsExpression('function (customer) { return customer.text; }'),
                                ],
                            ]) ?>

                            <?= $form->field($model, 'trafficLimit')->widget(TouchSpin::class, [
                                'pluginOptions' => [
                                    'min' => 1000,
                                    'max' => 500000,
                                    'step' => 100,
                                    'buttondown_txt' => "<i class='md-arrow-left'></i>",
                                    'buttonup_txt' => "<i class='md-arrow-right'></i>"
                                ]
                            ]) ?>

                            <?= $form->field($model, 'rateMin')->widget(TouchSpin::class, [
                                'pluginOptions' => [
                                    'min' => 0,
                                    'step' => 0.5,
                                    'decimals' => 1,
                                    'buttondown_txt' => "<i class='md-arrow-left'></i>",
                                    'buttonup_txt' => "<i class='md-arrow-right'></i>"
                                ]
                            ]) ?>

                            <?= $form->field($model, 'defaultLanguage')->dropDownList(Yii::$app->params['languages']) ?>

                            <?= $form->field($model, 'updateFrequency')->textInput() ?>

                            <?= $form->field($model, 'status')->dropDownList(WebsiteHelper::statusesList()) ?>

                            <div class="form-group">
                                <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php ActiveForm::end() ?>
    </div>
</div>