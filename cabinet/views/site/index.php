<?php

use yii\helpers\Url;
use yii\web\JsExpression;
use kartik\form\ActiveForm;
use dosamigos\chartjs\ChartJs;
use core\helpers\CustomerHelper;
use core\entities\Customer\Customer;
use cabinet\models\StatisticsSearch;

/**
 * @var yii\web\View $this
 * @var array $inTraffic
 * @var array $outTraffic
 * @var StatisticsSearch $searchModel
 * @var Customer $customer
 */

$this->title = Yii::$app->name;
$chartConfig = $searchModel->getChartConfig();

?>
<section class="dashboard-index">
    <div class="filters">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="white-panel">
                        <?php $form = ActiveForm::begin(['method' => 'GET', 'action' => '/', 'options' => ['class' => 'flex']]) ?>
                            <div class="by-date">
                                <div class="form-group">
                                    <input class="form-control range" type="text" name="range" title="Date range" value="<?= $searchModel->range ?>">
                                </div>
                            </div>
                            <!-- By Date-->

                            <div class="by-website">
                                <?= $form->field($searchModel, 'websiteId', ['options' => ['class' =>'form-group select']])
                                    ->dropDownList(CustomerHelper::websitesList($customer), ['prompt' => 'Bütün saytlar', 'onChange' => 'this.form.submit()'])
                                    ->label(false) ?>
                            </div>
                            <!-- By Website-->
                        <?php ActiveForm::end() ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Filters-->

    <div class="website-stats">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="white-panel">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="traffic-wrapper">
                                    <h2 class="block-title">Daxil olan traffik</h2>

                                    <div class="traffic-block flex">
                                        <div class="traffic-item">
                                            <p class="name">Keçid</p>
                                            <p class="qty"><?= $inTraffic['data']->getClicks() ?></p>
                                        </div>

                                        <div class="traffic-item">
                                            <p class="name">Görsənmə</p>
                                            <p class="qty"><?= $inTraffic['data']->getViews() ?></p>
                                        </div>

                                        <div class="traffic-item">
                                            <p class="name">CTR</p>
                                            <p class="qty"><?= $inTraffic['ctr'] ?>%</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Col-->

                            <div class="col-md-6">
                                <div class="traffic-wrapper">
                                    <h2 class="block-title">Göndərilən traffik</h2>

                                    <div class="traffic-block flex">
                                        <div class="traffic-item">
                                            <p class="name">Keçid</p>
                                            <p class="qty"><?= $outTraffic['data']->getClicks() ?></p>
                                        </div>

                                        <div class="traffic-item">
                                            <p class="name">Görsənmə</p>
                                            <p class="qty"><?= $outTraffic['data']->getViews() ?></p>
                                        </div>

                                        <div class="traffic-item">
                                            <p class="name">CTR</p>
                                            <p class="qty"><?= $outTraffic['ctr'] ?>%</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Col-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Websites Statistics-->

    <div class="stats-chart">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="white-panel">
                        <h2 class="block-title">Qrafik</h2>
                        <?php if (!empty($chartConfig)): ?>
                        <div class="chart" data-url="<?= Url::to(['site/chart'])?>">
                            <?= ChartJs::widget([
                                'type' => 'line',
                                'options' => [
                                    'height' => 150,
                                ],
                                'clientOptions' => [
                                    'legend' => [
                                        'onClick' => new JsExpression("function (event, legendItem) {
                                              var index = legendItem.datasetIndex;
                                              var ci = this.chart;
                                              var legends = [];
                                              ci.data.datasets.forEach(function(e, i) {
                                                var meta = ci.getDatasetMeta(i);
                                    
                                                if (i === index) {
                                                    meta.hidden = meta.hidden === null ? !ci.data.datasets[index].hidden : null;
                                                }
                                                legends.push(meta.hidden);
                                              });
                                              
                                              ci.update();

                                              $.ajax({
                                                 url: $('.chart').data('url'),
                                                 data: {legends: legends},
                                                 success: function(response){
                                                    console.log(response)
                                                 }
                                              });
                                        }")
                                    ],
                                ],
                                'data' => $chartConfig,
                            ]); ?>
                        </div>
                        <?php else: ?>
                            <p class="text-center">Göstərilən müddət üçün kifayət qədər statistika toplanmayıb</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Statistics Chart-->
</section>
<!-- Dashboard Index-->
