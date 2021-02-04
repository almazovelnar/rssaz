<?php

use yii\web\View;
use kartik\form\ActiveForm;
use yii\helpers\{Url, Html};
use core\forms\cabinet\ValidatorForm;

/**
 * @var View $this
 * @var ValidatorForm $model
 * @var LibXMLError[] $errors
 * @var bool $proceeded
 */

$this->title = 'Rss Validator';
$lines = !empty($errors) ? file($model->getLink()) : [];
?>

<section class="rss-validator">
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="white-panel">
                    <h2 class="block-title">RSS validasiyasi</h2>

                    <div class="row">
                        <div class="col-lg-8 offset-lg-2">
                            <?php $form = ActiveForm::begin(['method' => 'GET', 'action' => Url::to(['index']), 'options' => ['class' => 'rss-validation-form']]) ?>
                                <?= $form->field($model, 'link')->textInput(['placeholder' => 'RSS address'])->label(false) ?>

                                <button type="submit" class="btn-custom">Yoxla</button>
                            <?php ActiveForm::end() ?>
                            <!-- Validation Form-->
                        </div>
                    </div>
                    <!-- Row-->
                    <?php if ($proceeded): ?>
                    <div class="rss-v-results">
                        <?php if (!empty($errors)): ?>
                            <h3 class="v-status danger">Uğursuz validasiya</h3>
                            <?php foreach ($errors as $error): ?>
                                <div class="err-details">
                                    <?php if (isset($error->line) && $error->line): ?>
                                        <div class="message">line <?= $error->line ?>, column <?= $error->column ?>: <span class="err-highlight"><?= Html::encode($error->message) ?></span></div>
                                        <div class="highlight"><?= Html::encode(trim($lines[$error->line - 1] ?? '')) ?></div>
                                    <?php else: ?>
                                        <div class="message"><span class="err-highlight"><?= Html::encode($error->message) ?></span></div>
                                    <?php endif; ?>
                                </div>
                                <!-- Error Details-->
                            <?php endforeach; ?>
                        <?php else: ?>
                            <h3 class="v-status success">RSS uğurla validasiyadan keçdi.</h3>
                        <?php endif; ?>
                    </div>
                    <!-- Validation Results-->
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- RSS validation page-->