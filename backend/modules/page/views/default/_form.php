<?php

use dosamigos\ckeditor\CKEditor;
use kartik\switchinput\SwitchInput;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model \core\forms\manager\Page\Form */
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
                            <?= $form->field($translation, 'title')->textInput() ?>

                            <?= $form->field($translation, 'description')->widget(CKEditor::class, [
                                'options' => ['rows' => 6],
                                'preset' => 'full'
                            ]) ?>
                            <hr>
                            <div class="row">
                                <div class="col-lg-6">
                                    <?= $form->field($translation->meta, 'title')->textInput() ?>
                                </div>
                                <div class="col-lg-6">
                                    <?= $form->field($translation->meta, 'keywords')->textInput() ?>
                                </div>
                                <div class="col-lg-12">
                                    <?= $form->field($translation->meta, 'description')->textarea() ?>
                                </div>
                            </div>

                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="box box-warning">
                <div class="box-body">
                    <?= $form->field($model, 'slug')->textInput() ?>

                    <?= $form->field($model, 'type')->dropDownList($model->getTypesList()) ?>

                    <?= $form->field($model, 'status')->widget(SwitchInput::class) ?>

                    <?= $form->field($model, 'show')->widget(SwitchInput::class) ?>

                    <div class="form-group">
                        <?= Html::submitButton('Yadda saxla', ['class' => 'btn btn-success']) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
