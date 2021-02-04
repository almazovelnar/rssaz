<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model core\entities\Config */

$this->title = 'Update configuration: ' . $model->param;
$this->params['breadcrumbs'][] = ['label' => 'Config', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="config-form">
    <div class="box box-primary">
        <div class="box-header"></div>
        <div class="box-body">
            <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($model, 'value')->textarea(['rows' => 6]) ?>

            <?= $form->field($model, 'label')->textInput(['maxlength' => true]) ?>

            <div class="form-group">
                <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>

</div>

