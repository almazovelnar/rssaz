<?php

/* @var $this yii\web\View */
/* @var $user \core\entities\Customer\Customer */

$verifyLink = Yii::$app->cabinetUrlManager->createAbsoluteUrl(['auth/signup/confirm', 'token' => $user->email_confirm_token]);
?>
Hello <?= $user->getFullName() ?>,

Follow the link below to verify your email:

<?= $verifyLink ?>
