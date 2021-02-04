<?php

use kartik\switchinput\SwitchInput;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use backend\modules\user\helpers\UserHelper;

/* @var $this yii\web\View */
/* @var $model core\entities\User */

$this->title = 'Yeni istifadəçi';
$this->params['breadcrumbs'][] = ['label' => 'İstifadəçilər', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-create">
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
                        <h4>Şifrə</h4>
                    </div>
                    <div class="box-body">
                        <?= $form->field($model, 'password')->passwordInput(['maxLength' => true]); ?>
                        <?= $form->field($model, 'repeatPassword')->passwordInput(); ?>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="box box-primary">
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
