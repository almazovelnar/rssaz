<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user \core\entities\Customer\Customer */

$resetLink = Yii::$app->cabinetUrlManager->createAbsoluteUrl(['auth/reset/confirm', 'token' => $user->password_reset_token]);
?>
<div class="password-reset">
    <p>Hello <?= Html::encode($user->getFullName()) ?>,</p>

    <p>Follow the link below to reset your password:</p>

    <p><?= Html::a(Html::encode($resetLink), $resetLink) ?></p>
</div>
