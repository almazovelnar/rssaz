<?php

use core\helpers\CustomerHelper;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $customer core\entities\Customer\Customer */
/* @var $model \core\forms\manager\Customer\UpdateForm */

$this->title = 'Update Customer: ' . $customer->getFullName();
$this->params['breadcrumbs'][] = ['label' => 'Customers', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $customer->getFullName(), 'url' => ['view', 'id' => $customer->id]];
$this->params['breadcrumbs'][] = 'Update';

?>
<div class="customer-update">
    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-lg-6">
            <div class="box box-info">
                <div class="box-body">
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="upload-avatar-block">
                                <div
                                    <?php if ($customer->thumb): ?>
                                        class="image-preview image-loaded"
                                        style="background-image: url('<?= Yii::$app->storage->customer->getFile($customer->thumb) ?>')"
                                    <?php else: ?>
                                        class="image-preview"
                                    <?php endif; ?>
                                >
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
