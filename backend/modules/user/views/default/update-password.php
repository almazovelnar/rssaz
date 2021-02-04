<?php

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
           <div class="col-lg-7 col-lg-offset-2">
               <div class="box box-warning">
                   <div class="box-header">
                        <h3 class="text-center">Şifrənin dəyişdirilməsi</h3>
                   </div>
                   <div class="box-body">
                       <?= $form->field($model, 'newPassword')->passwordInput(['maxLength' => true]); ?>
                       <?= $form->field($model, 'repeatPassword')->passwordInput(); ?>

                       <div class="form-group">
                           <?= Html::submitButton('Yadda saxla', ['class' => 'btn btn-success pull-right']) ?>
                       </div>
                   </div>
               </div>
           </div>
        </div>
    <?php ActiveForm::end(); ?>
</div>

