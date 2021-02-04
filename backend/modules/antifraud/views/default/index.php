<?php

use yii\web\View;
use yii\helpers\Url;
use backend\widgets\GridViewRemark;
use backend\widgets\DatePickerRemark;
use kartik\form\ActiveForm;
use yii\helpers\ArrayHelper;
use kak\clickhouse\data\SqlDataProvider;
use core\entities\Customer\Website\Website;
use backend\modules\antifraud\models\TopIPSearch;

/**
 * @var View $this
 * @var SqlDataProvider $dataProvider
 * @var TopIPSearch $searchModel
 */

$this->title = 'Anti-fraud';
$websites = ArrayHelper::map(Website::find()->where(['!=', 'name', 'rss.az'])->asArray()->all(), 'id', 'name');
?>

<div class="row">
    <div class="col-md-12">
        <div class="diagnostics-index">
            <div class="box">
                <div class="box-header with-border">
                    <p>Sessions for <strong><?= $searchModel->date ?></strong></p>
                </div>
                <div class="box-body">
                    <?php $form = ActiveForm::begin([
                        'method' => 'GET',
                        'action' => Url::to(['index']),
                        'options' => ['onchange' => 'this.submit()']
                    ]) ?>
                        <div class="row">
                            <div class="col-md-4">
                                <?= $form->field($searchModel, 'website', ['options' => ['class' => 'form-group select']])
                                    ->dropDownList($websites, ['prompt' => 'All websites'])
                                    ->error(false) ?>
                            </div>
                            <div class="col-md-4">
                                <?= $form->field($searchModel, 'ip', ['options' => ['class' => 'form-group select']])
                                    ->textInput(['placeholder' => 'Enter IP for searching'])->error(false) ?>
                            </div>
                            <div class="col-md-4">
                                <?= $form->field($searchModel, 'date')->widget(DatePickerRemark::class, [
                                    'language' => 'en',
                                    'pluginOptions' => [
                                        'format' => 'yyyy-mm-dd',
                                        'language' => 'en',
                                    ]
                                ]) ?>
                            </div>
                        </div>
                    <?php ActiveForm::end() ?>
                    <br>
                    <?= GridViewRemark::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            [
                                'attribute' => 'website_id',
                                'label' => 'Website',
                                'value'  => function ($row) use ($websites) {
                                    return $websites[$row['website_id']] ?? null;
                                },
                            ],
                            [
                               'attribute' => 'ip',
                               'filter' => false,
                            ],
                            'agent',
                            'post_count',
                            'created_at',
                        ],
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
