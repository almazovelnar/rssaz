<?php

use kartik\form\ActiveForm;
use yii\web\View;

/**
 * @var View $this
 * @var array $websites
 */

$this->title = Yii::t('code', 'choose_site');
?>

<section class="generate-code-page">
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="white-panel">
                    <!-- Ajax Loader-->
                    <h2 class="block-title"><?= $this->title ?></h2>

                    <?php $form = ActiveForm::begin(['id' => 'select-website-form', 'enableClientValidation' => true]) ?>
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'website', ['options' => ['class' => 'form-group select']])
                                ->dropDownList($websites) ?>
                        </div>
                        <!-- Col-->

                        <div class="col-md-6">
                            <div class="form-group">
                                <button class="btn-custom action-generate" type="submit">
                                    <?= Yii::t('code', 'next') ?>
                                    <i class="material-icons">arrow_forward_ios</i>
                                </button>
                            </div>

                        </div>
                    </div>
                    <!-- Row-->
                    <?php ActiveForm::end() ?>
                </div>
                <!-- White Panel-->
            </div>
        </div>
    </div>
</section>