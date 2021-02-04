<?php

use backend\modules\user\helpers\UserHelper;
use core\helpers\CommonHelper;
use kartik\switchinput\SwitchInput;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var yii\web\View $this */
/* @var \core\forms\manager\User\CreateForm $model */
/* @var core\entities\User $user */

$this->title = 'Update User: ' . ucfirst($user->username);
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $user->username, 'url' => ['view', 'id' => $user->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="user-update">
    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-lg-4">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h4>Məlumatlar</h4>
                </div>
                <div class="box-body">
                    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>
                    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h4>Tənzimləmələr</h4>
                </div>
                <div class="box-body">
                    <?= $form->field($model, 'role')->dropDownList(UserHelper::rolesList()) ?>
                    <?= $form->field($model, 'status')->widget(SwitchInput::class) ?>

                    <div class="form-group">
                        <?= Html::submitButton('Yarat', ['class' => 'btn btn-success']) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>

