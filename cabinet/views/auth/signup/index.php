<?php

use kartik\select2\Select2;
use kartik\form\ActiveForm;
use himiklab\yii2\recaptcha\ReCaptcha3;

/* @var $this yii\web\View */
/* @var $model \core\forms\auth\cabinet\SignUpForm */

$this->title = Yii::t('signup', 'page_title');

?>
<section class="auth-page flex-center">
    <div class="register-form">
        <div class="logo text-center"><a href="/"><img src="<?= Yii::getAlias('@web/') ?>images/logo.svg"></a></div>

        <?php $form = ActiveForm::begin(['options' => ['id' => 'sign-up']]) ?>

        <h2 class="form-name"><?= Yii::t('signup', 'title') ?></h2>

        <?= $form->field($model, 'email')->textInput(['placeholder' => $model->getAttributeLabel('email')])->label(false) ?>

        <?= $form->field($model, 'password')->passwordInput(['placeholder' => $model->getAttributeLabel('password')])->label(false) ?>

        <?= $form->field($model, 'passwordRepeat')->passwordInput(['placeholder' => $model->getAttributeLabel('passwordRepeat')])->label(false) ?>

        <?= $form->field($model, 'name')->textInput(['placeholder' => $model->getAttributeLabel('name')])->label(false) ?>

        <?= $form->field($model, 'surname')->textInput(['placeholder' => $model->getAttributeLabel('surname')])->label(false) ?>

        <?= $form->field($model, 'sitesList')->widget(Select2::class, [
                'data' => false,
                'options' => [
                    'multiple' => true,
                    'placeholder' => $model->getAttributeLabel('sitesList'),
                    'id' => 'sitesList'
                ],
                'pluginOptions' => [
                    'tags' => true,
                    'initialize' => true,
                ],
                'pluginEvents' => [
                    "select2:open" => "function() { $('.select2-container').not('.select2').addClass('addingContainer') }",
                ]
        ])->label('<span class="info-label" data-toggle="tooltip" title="' . Yii::t('form_labels', 'sitesListInfo') . '">?</span>') ?>

        <?= $form->field($model, 'agree', [
            'contentAfterInput' => '<label for="agree">' . $model->getAttributeLabel('agree') . '</label>',
            'options' => ['class' => 'form-group use-terms'],
        ])->checkbox(['id' => 'agree'], false)->label(false) ?>

        <?= $form->field($model, 'reCaptcha')->widget(ReCaptcha3::class)->label(false) ?>

        <div class="submit-form">
            <button type="submit" class="btn-custom submit-register">
                <?= Yii::t('signup', 'submit_button') ?><i class="material-icons">arrow_forward_ios</i>
            </button>
        </div>

        <?php ActiveForm::end() ?>
    </div>
</section>

<?php $this->registerJs("
	$(function () {
        $('[data-toggle=\"tooltip\"]').tooltip()
    })

    $('#sign-up').on('submit', function() {
        return haveSites()
    })
    
    $('#sitesList').on('change', function() {
        return haveSites()
    })
    
    function haveSites() {
        if ($('#sitesList').val().length === 0) {
            $('#sitesList').closest('.field-sitesList').find('.select2').addClass('invalid')
            return false;
        } else {
            $('#sitesList').closest('.field-sitesList').find('.select2').removeClass('invalid')
            return true;
        }
    }
") ?>
