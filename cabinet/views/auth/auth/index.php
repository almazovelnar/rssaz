<?php

/* @var $this yii\web\View */

use himiklab\yii2\recaptcha\ReCaptcha3;
use kartik\form\ActiveForm;
use yii\helpers\Url;

/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \core\forms\auth\cabinet\LoginForm */

$this->title = Yii::t('login', 'page_title');

?>
<section class="auth-page flex-center">
    <div class="login-form">
        <div class="logo text-center">
            <a href="/"><img src="<?= Yii::getAlias('@web/') ?>images/logo.svg"></a>
        </div>

        <?php $form = ActiveForm::begin() ?>

        <h2 class="form-name"><?= Yii::t('login', 'title') ?></h2>

        <?= $form->field($model, 'username')->textInput(['placeholder' => $model->getAttributeLabel('username')])->label(false) ?>

        <?= $form->field($model, 'password')->passwordInput(['placeholder' => $model->getAttributeLabel('password')])->label(false) ?>

        <?= $form->field($model, 'reCaptcha')->widget(ReCaptcha3::class)->label(false) ?>

        <?= $form->field($model, 'rememberMe', [
            'contentAfterInput' => '<label for="rememberMe">' . $model->getAttributeLabel('rememberMe') . '</label>',
            'options' => ['class' => 'form-group use-terms'],
        ])->checkbox(['id' => 'rememberMe'], false)->label(false) ?>

        <div class="submit-form flex-center">
            <div>
                <a href="<?= Url::to(['auth/reset/request']) ?>"><?= Yii::t('login', 'forgot_password') ?></a>
                <br>
                <a href="<?= Url::to(['auth/signup/request']) ?>"><?= Yii::t('login', 'register') ?></a>
            </div>
            <button type="submit" class="btn-custom submit-login">
                <?= Yii::t('login', 'submit_button') ?><i class="material-icons">arrow_forward_ios</i>
            </button>
        </div>

        <?php ActiveForm::end() ?>
    </div>
</section>
