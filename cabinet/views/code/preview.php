<?php

use yii\web\View;
use yii\helpers\Url;
use kartik\form\ActiveForm;
use core\helpers\CodeHelper;
use kartik\touchspin\TouchSpin;
use core\forms\cabinet\Website\CodeForm;
use core\entities\Customer\Website\Code;

/**
 * @var View $this
 * @var array $websites
 * @var array $fonts
 * @var array $fontStyles
 * @var array $blocks
 * @var array $titleFontSizes
 * @var array $codeTypes
 * @var CodeForm $model
 * @var bool $hasCode
 */

$this->title = Yii::t('code', 'code_generation');
$this->registerCss($model->applyStyles());
?>
<section class="generate-code-page">
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="white-panel">
                    <!-- Ajax Loader-->
                    <h2 class="block-title"><?= $this->title ?></h2>
                    <div class="ajax-info"><span></span></div>

                    <ul class="nav nav-tabs <?= (!$hasCode) ? 'hidden' : null ?>" role="tablist">
                        <?php foreach ($codeTypes as $type => $config): ?>
                            <li class="nav-item">
                                <a class="nav-link <?= ($type == 'default' ? 'active' : null) ?>"
                                   href="#<?= $type ?>" data-toggle="tab">
                                    <?= $config['label'] ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>

                    <?php $form = ActiveForm::begin(['id' => 'code-generation-form', 'action' => Url::to(['generate-code']), 'enableClientValidation' => true]) ?>
                        <div class="row">
                            <div class="col-md-6">
                                <?= $form->field($model, 'website', ['options' => ['class' => 'form-group select']])
                                    ->dropDownList($websites, ['data-url' => Url::to(['load-customer-config'])]) ?>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <?= $form->field($model, 'blockCount')->widget(TouchSpin::class, [
                                            'options' => ['data-url' => Url::to(['code/append-block'])],
                                            'pluginOptions' => [
                                                'min' => Code::MIN_BLOCK_COUNT,
                                                'max' => Code::MAX_BLOCK_COUNT,
                                                'buttonup_txt' => '<i class="material-icons">keyboard_arrow_right</i>',
                                                'buttondown_txt' => '<i class="material-icons">keyboard_arrow_left</i>'
                                            ]
                                        ]) ?>
                                    </div>
                                    <!-- Col-->

                                    <div class="col-lg-6">
                                        <?= $form->field($model, 'blockWidth')->widget(TouchSpin::class, [
                                            'pluginOptions' => [
                                                'min' => Code::MIN_BLOCK_WIDTH,
                                                'max' => Code::MAX_BLOCK_WIDTH,
                                                'buttonup_txt' => '<i class="material-icons">keyboard_arrow_right</i>',
                                                'buttondown_txt' => '<i class="material-icons">keyboard_arrow_left</i>'
                                            ]
                                        ]) ?>
                                    </div>
                                    <!-- Col-->
                                </div>
                                <!-- Row-->

                                <div class="row">
                                    <div class="col-12">
                                        <div class="divider"></div>
                                    </div>
                                </div>
                                <!-- Row-->

                                <div class="row">
                                    <div class="col-md-12">
                                        <?= $form->field($model, 'titleFont', ['options' => ['class' => 'form-group select']])
                                            ->dropDownList(CodeHelper::getFonts()) ?>
                                    </div>
                                    <!-- Col-->

                                    <div class="col-md-6">
                                        <?= $form->field($model, 'titleStyle', ['options' => ['class' => 'form-group select']])
                                            ->dropDownList(CodeHelper::getFontStyles()) ?>
                                    </div>
                                    <!-- Col-->

                                    <div class="col-md-6">
                                        <?= $form->field($model, 'titleFontSize')->widget(TouchSpin::class, [
                                            'pluginOptions' => [
                                                'min' => Code::MIN_TITLE_SIZE,
                                                'max' => Code::MAX_TITLE_SIZE,
                                                'buttonup_txt' => '<i class="material-icons">keyboard_arrow_right</i>',
                                                'buttondown_txt' => '<i class="material-icons">keyboard_arrow_left</i>'
                                            ]
                                        ]) ?>
                                    </div>
                                    <!-- Col-->

                                    <div class="col-md-6">
                                        <?= $form->field($model, 'direction', ['options' => ['class' => 'form-group select']])
                                            ->dropDownList(CodeHelper::getDirections()) ?>
                                    </div>
                                    <!-- Col-->
                                </div>
                                <!-- Row-->

                            </div>
                            <!-- Col-->

                            <div class="col-md-6">
                                <div class="form-group">
                                    <button class="btn-custom action-generate" type="submit">
                                        <?= Yii::t('code', 'generate_code') ?>
                                        <i class="material-icons">arrow_forward_ios</i>
                                    </button>
                                </div>

                                <div class="tab-content">
                                    <?php foreach ($codeTypes as $type => $config): ?>
                                        <div class="tab-pane <?= ($type == 'default') ? 'active' : null ?>" role="tabpanel" id="<?= $type ?>">
                                            <div class="generated-code">
                                                <div class="form-group">
                                                    <textarea class="form-control" readonly><?= $config['content'] ?></textarea>
                                                </div>
                                                <button class="btn-custom copy-to-clipboard <?= (!$hasCode) ? 'hidden' : null ?>" type="button">
                                                    <?= Yii::t('code','copy_code') ?>
                                                    <i class="material-icons">arrow_forward_ios</i>
                                                </button>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>

                    <?php ActiveForm::end() ?>
                </div>
                <!-- White Panel-->

                <div class="white-panel">
                    <h2 class="block-title"><?= Yii::t('code', 'preview') ?></h2>

                    <div class="slider-preview">
                        <div class="horizontal-preview <?= $model->direction == 'horizontal' ? 'visible' : null ?>">
                            <div class="custom-slider">
                                <div class="slider-track"><?= $blocks ?></div>
                                <div class="prev-slide"></div>
                                <div class="next-slide"></div>
                            </div>
                        </div>

                        <div class="vertical-preview <?= $model->direction == 'vertical' ? 'visible' : null ?>">
                            <div class="blank-text">
                                <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Consectetur pariatur quos, vel necessitatibus doloremque earum, nobis recusandae aspernatur, maiores neque voluptas rem facilis accusamus sed quaerat ipsam inventore dolore adipisci.</p>
                                <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Placeat, odit veritatis cum deleniti corporis molestias similique ducimus minus quam beatae!</p>
                                <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Rem ratione iure ea, ipsum nobis vel fuga natus numquam sed similique illo omnis tempore maiores nam explicabo veniam eaque aut assumenda beatae reiciendis quisquam obcaecati, adipisci totam corporis! Debitis, mollitia autem, dolor corporis, iure fuga odit eaque necessitatibus dolore error quod!</p>
                                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Omnis sapiente soluta fugit quis quia provident natus, architecto reiciendis! Quibusdam culpa accusamus, dolores ullam reiciendis sunt dignissimos enim excepturi asperiores perferendis possimus illum odit, at, exercitationem quisquam laborum placeat tempore vitae eligendi? Nobis vitae magnam, iusto expedita mollitia odit porro at assumenda doloremque facilis similique ratione recusandae, veniam inventore reprehenderit ea fuga quibusdam nostrum eligendi harum? Saepe perferendis inventore quis accusamus?</p>
                            </div>
                                        
                            <div class="custom-slider">
                                <div class="slider-track"><?= $blocks ?></div>
                                <div class="prev-slide"></div>
                                <div class="next-slide"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>