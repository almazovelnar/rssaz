<?php

use yii\web\View;
use yii\helpers\Url;
use backend\widgets\GridViewRemark;
use backend\widgets\DatePickerRemark;
use kartik\form\ActiveForm;
use yii\helpers\ArrayHelper;
use kak\clickhouse\data\SqlDataProvider;
use core\entities\Customer\Website\Website;
use backend\modules\antifraud\models\TopAgentSearch;


/**
 * @var View $this
 * @var SqlDataProvider $dataProvider
 * @var TopAgentSearch $searchModel
 */

$this->title = 'Anti-fraud | TOP User Agent';
$websites = ArrayHelper::map(Website::find()->where(['!=', 'name', 'rss.az'])->asArray()->all(), 'id', 'name');
?>

<div class="row">
    <div class="col-md-12">
        <div class="diagnostics-index">
            <div class="box">
                <div class="box-header with-border">
                    <p>TOP User Agent sessions for <strong><?= $searchModel->date ?></strong></p>
                </div>
                <div class="box-body">
                    <?php $form = ActiveForm::begin([
                        'method' => 'GET',
                        'action' => Url::to(['index']),
                        'options' => ['onchange' => 'this.submit()']
                    ]) ?>
                        <div class="row">

                            <div class="col-md-3">
                                <?= $form->field($searchModel, 'website', ['options' => ['class' => 'form-group select']])
                                    ->dropDownList($websites, ['prompt' => 'All websites'])
                                    ->error(false) ?>
                            </div>
                            <div class="col-md-2">
                                <?= $form->field($searchModel, 'ip', ['options' => ['class' => 'form-group select']])
                                    ->textInput()->error(false) ?>
                            </div>
                            <div class="col-md-3">
                                <?= $form->field($searchModel, 'agent', ['options' => ['class' => 'form-group select']])
                                    ->textInput()->error(false) ?>
                            </div>
                            <div class="col-md-2">
                                <?= $form->field($searchModel, 'date')->widget(DatePickerRemark::class, [
                                    'language' => 'en',
                                    'pluginOptions' => [
                                        'format' => 'yyyy-mm-dd',
                                        'language' => 'en',
                                    ]
                                ]) ?>
                            </div>
                            <div class="col-md-2">
                                <?= $form->field($searchModel, 'type', ['options' => ['class' => 'form-group select']])
                                    ->dropDownList(TopAgentSearch::types())
                                    ->error(false) ?>
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
                                'attribute' => 'agent',
                                'filter' => false
                            ],
                            $searchModel->type,
                        ],
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
