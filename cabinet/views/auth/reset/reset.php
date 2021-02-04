<?php

/**
 * @var \yii\web\View $this
 * @var \core\forms\auth\cabinet\PasswordResetForm $model
 */

use kartik\form\ActiveForm;

$this->title = Yii::t('reset', 'reset_page_title');

?>
<section class="auth-page flex-center">
    <div class="password-reset-request-form">
        <div class="logo text-center">
            <a href="/"><img src="<?= Yii::getAlias('@web/') ?>images/logo.svg"></a>
        </div>

        <?php $form = ActiveForm::begin() ?>

        <h2 class="form-name"><?= Yii::t('reset', 'reset_title') ?></h2>

        <?= $form->field($model, 'password')->passwordInput(['placeholder' => $model->getAttributeLabel('password')])->label(false) ?>

        <?= $form->field($model, 'passwordRepeat')->passwordInput(['placeholder' => $model->getAttributeLabel('passwordRepeat')])->label(false) ?>

        <div class="submit-form">
            <button type="submit" class="btn-custom submit-register">
                <?= Yii::t('reset', 'reset_submit_button') ?><<i class="material-icons">arrow_forward_ios</i>
            </button>
        </div>

        <?php ActiveForm::end() ?>
    </div>
</section>