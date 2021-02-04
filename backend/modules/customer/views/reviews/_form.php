<?php

use yii\web\JsExpression;
use kartik\select2\Select2;
use yii\widgets\ActiveForm;
use kartik\switchinput\SwitchInput;
use yii\helpers\{Html, Url, ArrayHelper};
use core\entities\Customer\Website\Website;

/* @var $this yii\web\View */
/* @var $model \core\forms\manager\CustomerReview\Form */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="page-form">
    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-lg-9">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <?php foreach (Yii::$app->params['languages'] as $code => $label): ?>
                        <li class="nav-item">
                            <a href="#<?= $code ?>" data-toggle="tab" class="nav-link <?= $code == Yii::$app->params['defaultLanguage'] ? 'active' : '' ?>"><?= $label ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <div class="tab-content">
                    <?php foreach ($model->translations as $translation): ?>
                        <div class="tab-pane <?= $translation->language == Yii::$app->params['defaultLanguage'] ? 'active' : '' ?>" id="<?= $translation->language ?>">
                            <?= $form->field($translation, 'review')->textarea(['rows' => 8]) ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="box box-warning">
                <div class="box-body">

                    <?= $form->field($model, 'websiteId')->widget(Select2::class, [
                        'data' => ArrayHelper::map(Website::find()
                            ->where(['status' => Website::STATUS_ACTIVE])
                            ->asArray()
                            ->orderBy('name ASC')
                            ->all(), 'id',
                            fn(array $website) => $website['name']
                        ),
                        'options' => ['prompt' => 'Select a website...'],
                        'pluginOptions' => [
                        'allowClear' => true
                        ],
                    ]) ?>

                    <?= $form->field($model, 'status')->widget(SwitchInput::class) ?>

                    <div class="form-group">
                        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
