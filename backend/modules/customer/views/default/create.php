<?php

use core\helpers\CustomerHelper;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $model \core\forms\manager\Customer\CreateForm */
/* @var $this yii\web\View */

$this->title = 'Create Customer';
$this->params['breadcrumbs'][] = ['label' => 'Customers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="customer-form">
    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-lg-6">
            <div class="box box-info">
                <div class="box-body">
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="upload-avatar-block">
                                <div class="image-preview">
                                    <span>No image</span>
                                </div>
                                <?= $form->field($model, 'thumbFile')->fileInput(['class' => 'upload-avatar hidden'])->label(false) ?>
                                <div class="btn-group btn-group-justified">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-primary trigger-photo-upload">
                                            <i class="md md-camera"></i>
                                        </button>
                                    </div>
                                    <div class="btn-group hidden">
                                        <button type="button" class="btn btn-danger trigger-photo-delete">
                                            <i class="md md-delete"></i>
                                        </button>
                                    </div>
                                </div>
                                <br>
                            </div>
                        </div>
                        <div class="col-lg-9">
                            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

                            <?= $form->field($model, 'surname')->textInput(['maxlength' => true]) ?>

                            <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

                            <?= $form->field($model, 'password')->passwordInput() ?>

                            <?= $form->field($model, 'repeatPassword')->passwordInput() ?>

                            <?= $form->field($model, 'status')->dropDownList(CustomerHelper::statusesList()) ?>
                            <div class="form-group">
                                <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
