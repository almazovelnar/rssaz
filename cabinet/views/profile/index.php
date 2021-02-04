<?php

use kartik\form\ActiveForm;
use yii\helpers\Url;

/**
 * @var \yii\web\View $this
 * @var \core\entities\Customer\Customer $customer
 * @var \core\forms\cabinet\Profile\UpdateForm $model
 */

$this->title = 'Profili redaktə et';

?>
<section class="edit-profile">
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="white-panel">
                    <div class="title-links flex">
                        <h2 class="block-title"><?= $this->title ?></h2>

                        <div class="links flex">

                            <a class="btn-custom" href="<?= Url::to(['change-password']) ?>">Şifrəni dəyiş<i class="material-icons">arrow_forward_ios</i></a>
                        </div>
                    </div>

                    <?php $form = ActiveForm::begin(['options' => ['class' => 'flex']]) ?>
                        <div class="upload-avatar-block">
                            <?php if ($customer->thumb): ?>
                                <div class="image-preview" style="background-image: url('<?= Yii::$app->storage->customer->getFile($customer->thumb) ?>')"></div>
                            <?php else: ?>
                                <div class="image-preview"><span>No image</span></div>
                            <?php endif; ?>

                            <?= $form->field($model, 'thumbFile')->fileInput(['class' => 'upload-avatar hidden'])->label(false) ?>

                            <button class="trigger-image-upload" type="button">Yenilə</button>
                        </div>
                        <!-- Avatar Upload-->

                        <div class="profile-info">
                            <div class="row">
                                <div class="col-lg-6">
                                    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                                </div>
                                <!-- Col-->

                                <div class="col-lg-6">
                                    <?= $form->field($model, 'surname')->textInput(['maxlength' => true]) ?>
                                </div>
                                <!-- Col-->

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input title="email" class="form-control" value="<?= $customer->email ?>" disabled>
                                    </div>
                                </div>
                                <!-- Col-->

                                <div class="col-lg-6">
                                    <button class="btn-custom submit-edit-profile">Yadda saxla<i class="material-icons">arrow_forward_ios</i></button>
                                </div>
                                <!-- Col-->
                            </div>
                        </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Edit Profile Page-->
