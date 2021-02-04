<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \core\forms\auth\backend\LoginForm */

$this->title = 'Sign In';

$usernameOptions = [
    'options' => ['class' => 'form-group form-material floating', 'data-plugin' => 'formMaterial'],
    'template' => "{input}\n{label}",
    'inputTemplate' => "{input}",
    'inputOptions' => ['class' => 'form-control empty'],
    'labelOptions' => [ 'class' => 'floating-label']
];

$passwordOptions = [
    'options' => ['class' => 'form-group form-material floating', 'data-plugin' => 'formMaterial'],
    'template' => "{input}\n{label}",
    'inputTemplate' => "{input}",
    'inputOptions' => ['class' => 'form-control empty'],
    'labelOptions' => [ 'class' => 'floating-label']
];


?>
<!-- Page -->
    <div class="page-content">
        <div class="page-brand-info">
            <div class="brand">
                <img class="brand-img" src="<?= Yii::getAlias('@remark/assets/images/logo@2x.png') ?>" alt="...">
                <h2 class="brand-text font-size-40"><?= Yii::$app->name ?></h2>
            </div>
        </div>

        <div class="page-login-main">
            <div class="brand hidden-md-up">
                <img class="brand-img" src="<?= Yii::getAlias('@remark/assets/images/logo-colored@2x.png') ?>" alt="...">
                <h3 class="brand-text font-size-30"><?= Yii::$app->name ?></h3>
            </div>
            <h3 class="font-size-24">Admin panel-ə giriş</h3>
                <?php $form = ActiveForm::begin(['id' => 'login-form', 'enableClientValidation' => false]); ?>

                    <?= $form
                        ->field($model, 'username', $usernameOptions)
                        ->textInput() ?>

                    <?= $form
                        ->field($model, 'password', $passwordOptions)
                        ->passwordInput() ?>

                    <?= $form->field($model, 'rememberMe')->checkbox() ?>

                    <?= Html::submitButton('Daxil ol', ['class' => 'btn btn-primary btn-block', 'name' => 'login-button']) ?>

                <?php ActiveForm::end(); ?>
            <footer class="page-copyright">
                <p>Copyright © <a href="https://rss.az">Rss.az</a></p>
            </footer>
        </div>

    </div>
<!-- End Page -->