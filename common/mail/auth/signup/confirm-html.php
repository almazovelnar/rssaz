<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user \core\entities\Customer\Customer */

$verifyLink = Yii::$app->cabinetUrlManager->createAbsoluteUrl(['auth/signup/confirm', 'token' => $user->email_confirm_token]);

?>
<div class="verify-email">
    <p>Hello <?= Html::encode($user->getFullName()) ?>,</p>

    <p>Follow the link below to verify your email:</p>

    <p><?= Html::a(Html::encode($verifyLink), $verifyLink) ?></p>
</div>
