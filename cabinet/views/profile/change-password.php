<?php

use kartik\form\ActiveForm;

/**
 * @var \yii\web\View $this
 * @var \core\forms\cabinet\Profile\ChangePasswordForm $model
 */

$this->title = 'Şifrəni dəyiş';

?>
<section class="reset-password">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 offset-lg-1">
                <div class="white-panel">
                    <h2 class="block-title"><?= $this->title ?></h2>

                    <?php $form = ActiveForm::begin() ?>
                        <div class="row">
                            <div class="col-sm-6">
                                <?= $form->field($model, 'oldPassword')->passwordInput() ?>
                            </div>
                            <!-- Col-->

                            <div class="col-sm-6">
                                <?= $form->field($model, 'newPassword')->passwordInput() ?>
                            </div>
                            <!-- Col-->

                            <div class="col-sm-6">
                                <?= $form->field($model, 'repeatNewPassword')->passwordInput() ?>
                            </div>
                            <!-- Col-->

                            <div class="col-sm-6">
                                <button class="btn-custom submit-edit-password">Yadda saxla<i class="material-icons">arrow_forward_ios</i></button>
                            </div>
                            <!-- Col-->
                        </div>
                    <?php ActiveForm::end() ?>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Reset Password Page-->
