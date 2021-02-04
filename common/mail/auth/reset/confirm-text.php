<?php

/* @var $this yii\web\View */
/* @var $user \core\entities\Customer\Customer */

$resetLink = Yii::$app->cabinetUrlManager->createAbsoluteUrl(['auth/reset/confirm', 'token' => $user->password_reset_token]);
?>
Hello <?= $user->getFullName() ?>,

Follow the link below to reset your password:

<?= $resetLink ?>
