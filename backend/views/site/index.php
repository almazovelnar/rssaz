<?php

use kartik\select2\Select2;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\bootstrap\ActiveForm;
use dosamigos\chartjs\ChartJs;
use backend\models\SiteSearch;
use core\entities\Customer\Customer;
use kartik\daterange\DateRangePicker;

/* @var $this yii\web\View */
/* @var Customer $customer */
/* @var array $websites */
/* @var SiteSearch $searchModel */
/* @var array $inTraffic */
/* @var array $outTraffic */

$this->title = Yii::$app->name;
$chartConfig = $searchModel->getChartConfig();
?>
<div class="site-index">
    <div class="row">
        <div class="col-md-12">

            <?php $form = ActiveForm::begin(['method' => 'GET', 'action' => '/', 'options' => ['class' => 'flex', 'autocomplete' => 'off']]) ?>
                <div class="row w100">
                    <div class="col-md-4">
                        <div class="by-date">
                            <div class="form-group">
                                <?= DateRangePicker::widget([
                                    'model' => $searchModel,
                                    'attribute' => 'range',
                                    'convertFormat' => true,
                                    'options' => ['class' => 'form-control', 'placeholder' => 'Enter date range for statistics'],
                                    'pluginOptions' => [
                                        'timePicker' => true,
                                        'timePickerIncrement' => 30,
                                        'locale' => ['format' => 'Y-m-d'],
                                        'ranges' => [
                                            Yii::t('kvdrp', "Today") => ["moment().startOf('day')", "moment()"],
                                            Yii::t('kvdrp', "Yesterday") => ["moment().startOf('day').subtract(1,'days')", "moment().endOf('day').subtract(1,'days')"],
                                            Yii::t('kvdrp', "Last {n} Days", ['n' => 7]) => ["moment().startOf('day').subtract(6, 'days')", "moment()"],
                                            Yii::t('kvdrp', "Last {n} Days", ['n' => 30]) => ["moment().startOf('day').subtract(29, 'days')", "moment()"],
                                        ],
                                    ],
                                    'pluginEvents' => [
                                        "apply.daterangepicker" => "function() {this.form.submit()}"
                                    ],
                                ]);
                                ?>
                            </div>
                        </div>
                    </div>
                    <!-- By Date-->

                    <div class="col-md-4">
                        <div class="by-website">
                            <?= $form->field($searchModel, 'websiteId', ['options' => ['class' => 'form-group select']])
                                ->dropDownList($websites, ['prompt' => 'All websites', 'onChange' => 'this.form.submit()'])
                                ->label(false) ?>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="by-algorithm">
                            <?= Select2::widget([
                                'model' => $searchModel,
                                'attribute' => 'algorithm',
                                'data' => $searchModel->getAlgorithmList(),
                                'options' => ['placeholder' => 'Select a algorithm ...', 'onChange' => 'this.form.submit()'],
                                'pluginOptions' => ['allowClear' => true],
                              ]);
                            ?>
                        </div>
                    </div>
                    <!-- By Algorithm -->
                </div>
            <?php ActiveForm::end() ?>


            <div class="row">
                <div class="col-md-6">
                    <div class="traffic-wrapper">
                        <h2 class="block-title">Incoming traffic</h2>
                        <br>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="traffic-item">
                                    <p class="name"><b>Click</b></p>
                                    <p class="qty"><strong><?= $inTraffic['clicks'] ?></strong></p>
                                    <?php /*if (!empty($inTraffic['referrers'])): ?>
                                        <ul>
                                            <?php foreach ($inTraffic['referrers'] as $referrer => $value): ?>
                                                <li><?= $referrer ?>: <strong><?= $value['in_clicks'] ?> </strong>clicks.</li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php endif;*/ ?>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="traffic-item">
                                    <p class="name"><b>View</b></p>
                                    <p class="qty"><strong><?= $inTraffic['views'] ?></strong></p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="traffic-item">
                                    <p class="name"><b>CTR</b></p>
                                    <p class="qty"><strong><?= $inTraffic['ctr'] ?>%</strong></p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <!-- Col-->

                <div class="col-md-6">
                    <div class="traffic-wrapper">
                        <h2 class="block-title">Outgoing traffic</h2>
                        <br>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="traffic-item">
                                    <p class="name"><b>Click</b></p>
                                    <p class="qty"><strong><?= $outTraffic['clicks'] ?></strong></p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="traffic-item">
                                    <p class="name"><b>View</b></p>
                                    <p class="qty"><strong><?= $outTraffic['views'] ?></strong></p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="traffic-item">
                                    <p class="name"><b>CTR</b></p>
                                    <p class="qty"><strong><?= $outTraffic['ctr'] ?>%</strong></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Col-->
            </div>
            <hr>

            <!-- Chart -->
            <div class="row">
                <div class="col-md-12">
                    <?php if (!empty($chartConfig)): ?>
                        <div class="chart" data-url="<?= Url::to(['site/chart'])?>">
                            <?= ChartJs::widget([
                                'type' => 'line',
                                'options' => [
                                    'height' => '110px',
                                ],
                                'clientOptions' => [
                                    'legend' => [
                                        'position' => 'left',
                                        'onClick' => new JsExpression("function (event, legendItem) {
                                              var index = legendItem.datasetIndex;
                                              var ci = this.chart;
                                              var legends = [];
                                              ci.data.datasets.forEach(function(e, i) {
                                                var meta = ci.getDatasetMeta(i);
                                                if (i === index)
                                                    meta.hidden = meta.hidden === null ? !ci.data.datasets[index].hidden : null;
                                                legends.push(meta.hidden);
                                              });
                                              ci.update();
                                              $.ajax({
                                                 url: $('.chart').data('url'),
                                                 data: {legends: legends},
                                              });
                                        }")
                                    ],
                                ],
                                'data' => $chartConfig
                            ]); ?>
                        </div>
                    <?php else: ?>
                        <p class="text-center">We couldn't find any result for provided date range.</p>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
</div>
