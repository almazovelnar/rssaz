<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var yii\web\View $this */
/* @var \core\forms\manager\User\CreateForm $model */
/* @var \core\entities\Customer\Customer $customer */

$this->title = 'Update password: ' . $customer->getFullName();
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $customer->getFullName(), 'url' => ['view', 'id' => $customer->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="user-update">
    <?php $form = ActiveForm::begin(); ?>
        <div class="row">
           <div class="col-lg-7 col-lg-offset-2">
               <div class="box box-warning">
                   <div class="box-header">
                        <h3 class="text-center">Update password</h3>
                   </div>
                   <div class="box-body">
                       <?= $form->field($model, 'newPassword')->passwordInput(['maxLength' => true]); ?>
                       <?= $form->field($model, 'repeatPassword')->passwordInput(); ?>

                       <div class="form-group">
                           <?= Html::submitButton('Update', ['class' => 'btn btn-success pull-right']) ?>
                       </div>
                   </div>
               </div>
           </div>
        </div>
    <?php ActiveForm::end(); ?>
</div>

