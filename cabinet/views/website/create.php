<?php

/**
 * @var \yii\web\View $this
 * @var \core\forms\cabinet\Website\CreateForm $model
 */

use kartik\form\ActiveForm;
use kartik\touchspin\TouchSpin;

$this->title = 'Yeni sayt əlavə et';

?>
<section class="add-website-page">
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="white-panel">
                    <h2 class="block-title">Yeni sayt əlavə et</h2>
                    <?php $form = ActiveForm::begin() ?>
                        <div class="row">
                            <div class="col-sm-6">
                                <?= $form->field($model, 'name') ?>

                                <?= $form->field($model, 'address')->textInput(['placeholder' => 'http://yoursite.com']) ?>

                                <?= $form->field($model, 'language', [
                                    'options' => ['class' => 'form-group select']
                                ])->dropDownList(Yii::$app->params['languages']) ?>
                            </div>
                            <!-- Col-->

                            <div class="col-sm-6">
                                <?= $form->field($model, 'trafficLimit')->widget(TouchSpin::class, [
                                    'pluginOptions' => [
                                        'min' => 1000,
                                        'max' => 500000,
                                        'step' => 100,
                                        'buttonup_txt' => '<i class="material-icons">keyboard_arrow_right</i>',
                                        'buttondown_txt' => '<i class="material-icons">keyboard_arrow_left</i>'
                                    ]
                                ]) ?>

                                <?= $form->field($model, 'rssAddress')->textInput(['placeholder' => 'http://yoursite.com/rss.xml']) ?>

                                <button type="submit" class="btn-custom submit-add-website">Əlavə et<i class="material-icons">arrow_forward_ios</i></button>
                            </div>
                            <!-- Col-->
                        </div>
                        <!-- Row-->
                    <?php ActiveForm::end() ?>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Add Website Page-->
