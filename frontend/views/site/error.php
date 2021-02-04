<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = Yii::t('main', $name);
?>
<section class="not-found">
    <div class="container">
        <div class="row">
            <div class="col">
                <h1><?= Html::encode($this->title) ?></h1>
                <p>
                    <?= Yii::t('main', 'The above error occurred while the Web server was processing your request.')?>
                </p>
                <p>
                    <?= Yii::t('main', 'Please contact us if you think this is a server error. Thank you.')?>
                </p>
            </div>
        </div>
    </div>
</section>
