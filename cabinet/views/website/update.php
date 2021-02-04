<?php

use yii\web\View;
use yii\helpers\{Url, Html};
use core\entities\Customer\Website\Website;
use core\forms\cabinet\Website\UpdateForm;
use kartik\{form\ActiveForm, select2\Select2, touchspin\TouchSpin};

/**
 * @var View $this
 * @var UpdateForm $model
 * @var Website $website
 */

$this->title = 'Saytı redaktə et';
?>
<section class="edit-website-page">
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="white-panel">
                    <h2 class="block-title">Saytı redaktə et</h2>
                    <?php $form = ActiveForm::begin() ?>
                        <div class="row">
                            <div class="col-sm-6">
                                <?= $form->field($model, 'name', ['enableAjaxValidation' => true]) ?>
                            </div>
                            <!-- Col-->

                            <div class="col-sm-4">
                                <?= $form->field($model, 'trafficLimit')->widget(TouchSpin::class, [
                                    'pluginOptions' => [
                                        'min' => 1000,
                                        'max' => 500000,
                                        'step' => 100,
                                        'buttonup_txt' => '<i class="material-icons">keyboard_arrow_right</i>',
                                        'buttondown_txt' => '<i class="material-icons">keyboard_arrow_left</i>'
                                    ]
                                ]) ?>
                            </div>

                            <div class="col-sm-2">
                                <?= $form->field($model, 'defaultLanguage', [
                                    'options' => ['class' => 'form-group select']
                                ])->dropDownList(Yii::$app->params['languages']) ?>
                            </div>
                            <!-- Col-->
                        </div>
                        <!-- Row-->

                        <div class="row">
                            <div class="col-12">
                                <div class="divider"></div>
                            </div>
                        </div>
                        <!-- Row-->

                        <div class="row">
                            <div class="col-12 sitemap-links">
                                <div class="actions flex">
                                    <?php foreach ($model->rss as $rssForm): ?>
                                    <?php if (!$rssForm->isEmpty()) continue; ?>
                                        <button class="btn-dark toggle-rss" type="button" data-toggle="rss-<?= $rssForm->language ?>">
                                            <i class="material-icons">add_circle_outline</i>
                                            <?= Yii::t('website', 'add_' . $rssForm->language . '_rss_link') ?>
                                        </button>
                                    <?php endforeach; ?>
                                </div>

                                <div class="error-message"><?= $model->getFirstError('requiredRssAddress') ?></div>

                                <?php foreach ($model->rss as $rssForm): ?>
                                    <?= $form->field($rssForm, 'rssAddress', [
                                        'addon' => [
                                                'prepend' => ['content' => $website->address],
                                                'append' => [
                                                    'content' => Html::button('Yoxla', [
                                                        'class' => 'btn btn-secondary test-rss-uri',
                                                        'data-url' => Url::to(['validate-rss', 'language' => $rssForm->language])
                                                    ]),
                                                    'asButton' => true,
                                                ]
                                            ],
                                            'options' => [
                                                'class' => $rssForm->isEmpty() ? 'form-group hidden' : 'form-group',
                                                'id' => 'rss-' . $rssForm->language,
                                            ],
                                        ])
                                        ->textInput(['placeholder' => '/rss.xml', 'data-domain' => $website->address])
                                        ->hint(' ', ['class' => 'input-hint text-right hidden'])
                                        ->label($rssForm->language == $website->default_lang
                                            ? 'Saytın əsas dili üçün RSS linkini qeyd edin'
                                            : Yii::t('website', 'add_' . $rssForm->language . '_rss_link')
                                        );
                                    ?>
                                <?php endforeach; ?>
                            </div>
                            <!-- Col-->
                        </div>
                        <!-- Row-->
                        <div class="row">
                            <div class="col-12">
                                <div class="divider"></div>
                            </div>
                        </div>
                        <!-- Row-->
                        <div class="row">
                            <div class="col-lg-4">
                                <?= $form->field($model, 'updateFrequency', ['options' => ['class' => 'form-group select']])
                                    ->textInput(['type' => 'number']) ?>
                            </div>
                            <!-- Col-->

                            <div class="col-lg-8">
                                <?= $form->field($model, 'blockedDomains')
                                    ->dropDownList($model->blockedDomainsList, [
                                        'multiple' => 'multiple',
                                        'class' => 'form-control tags',
                                        'data-url' => Url::to(['domains', 'id' => $website->getId()])
                                    ]) ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <?= $form->field($model, 'whiteListedDomains')
                                    ->dropDownList($model->whiteListedDomainsList, [
                                        'multiple' => 'multiple',
                                        'class' => 'form-control tags',
                                        'data-url' => Url::to(['domains', 'id' => $website->getId()])
                                    ]) ?>
                            </div>
                        </div>

                        <div class="row">
                            <button type="submit" class="btn-custom submit-edit-website">
                                Yadda saxla<i class="material-icons">arrow_forward_ios</i>
                            </button>
                        </div>
                        <!-- Row-->
                    <?php ActiveForm::end() ?>
                </div>
            </div>
        </div>
    </div>
</section>
