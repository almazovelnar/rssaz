<?php

use dosamigos\chartjs\ChartJs;
use yii\web\JsExpression;
use yii\web\View;
use core\entities\Parse\Parse;
use yii\data\ActiveDataProvider;
use yii\helpers\{ArrayHelper, Html, Url};
use core\entities\Customer\Website\Website;
use backend\widgets\{GridViewRemark, DatePickerRemark};
use backend\modules\statistics\models\StatisticsSearch;

/**
 * @var View $this
 * @var ActiveDataProvider $dataProvider
 * @var StatisticsSearch $searchModel
 */

$this->title = 'Statistics';
$chartConfig = $searchModel->getChartConfig();

?>
<!-- Chart -->
<div class="row">
    <div class="col-md-12">
        <?php if (!empty($chartConfig)): ?>
            <div class="chart" data-url="<?= Url::to(['site/chart'])?>">
                <?= ChartJs::widget([
                    'type' => 'line',
                    'options' => [
                        'height' => '90px',
                    ],
                    'clientOptions' => [
                        'legend' => [
                            'display' => false
                        ],
                        'tooltips' => [
                            'callbacks'=> [
                                'title'=> new JsExpression("function(t, d) {
                                     var website = d.labels[t[0].index];
                                     var rss = d.addresses[t[0].index];
                                     var title = [website, rss, '']
                                     return title;
                                 }"),

                                'label'=> new JsExpression("function(t, d) {
                                         var label = d.labels[t.index];
                                         var data = d.datasets[t.datasetIndex].data[t.index];
                                         var date = d.dates[t.index]
                                         var time = 'Average time: ' + data + 's';
                                         var createdAt = 'Created at: ' + date;
                                         return [time, createdAt];
                                  }")
                            ]
                        ]
                    ],
                    'data' => $chartConfig
                ]); ?>
            </div>
        <?php else: ?>
            <p class="text-center">We couldn't find any result for provided date.</p>
        <?php endif; ?>
    </div>
</div>
<br><hr><br>
<div class="diagnostics-index">
    <div class="box">
        <div class="box-header with-border">
        </div>
        <div class="box-body">
            <?= GridViewRemark::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,

                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'attribute' => 'website',
                        'filter' => false,
                        'filterInputOptions' => ['prompt' => 'All websites', 'class' => 'form-control'],
                        'value' => function (Parse $parse) {
                            $website = Website::find()->andWhere(['id' => $parse->website_id])->first();
                            if ($website) return Html::a($website->name, $website->address, ['target' => '_blank']);
                            return null;
                        },
                        'format' => 'raw',
                    ],
                    [
                        'attribute' => 'successCount',
                        'label' => 'Success',
                        'value'  => function (Parse $parse) {
                            return Html::a('<span class="badge badge-success">' . (int) $parse->successCount . '</span>', ['/statistics/default/view', 'website' => $parse->website_id, 'status' => LIBXML_ERR_NONE, 'date' => $parse->created_at]);
                        },
                        'format' => 'raw'
                    ],
                    [
                        'attribute' => 'warningCount',
                        'label' => 'Warning',
                        'value'  => function (Parse $parse) {
                            return Html::a('<span class="badge badge-warning">' . (int) $parse->warningCount . '</span>', ['/statistics/default/view', 'website' => $parse->website_id, 'status' => LIBXML_ERR_WARNING, 'date' => $parse->created_at]);
                        },
                        'format' => 'raw',
                    ],
                    [
                        'attribute' => 'dangerCount',
                        'label' => 'Danger',
                        'value'  => function (Parse $parse) {
                            return Html::a('<span class="badge badge-danger">' . (int) $parse->dangerCount . '</span>', ['/statistics/default/view', 'website' => $parse->website_id, 'status' => LIBXML_ERR_ERROR, 'date' => $parse->created_at]);
                        },
                        'format' => 'raw',
                    ],
                    [
                        'attribute' => 'fatalCount',
                        'label' => 'Fatal',
                        'value'  => function (Parse $parse) {
                            return Html::a('<span class="badge badge-dark">' . (int) $parse->fatalCount . '</span>', ['/statistics/default/view', 'website' => $parse->website_id, 'status' => LIBXML_ERR_FATAL, 'date' => $parse->created_at]);
                        },
                        'format' => 'raw',
                    ],
                    [
                        'attribute' => 'created_at',
                        'label' => 'Parsed at',
                        'filter' => DatePickerRemark::widget([
                            'model' => $searchModel,
                            'attribute' => 'date',
                            'separator' => '-',
                            'pluginOptions' => [
                                'todayHighlight' => true,
                                'autoclose' => true,
                                'format' => 'yyyy-mm-dd'
                            ],
                            'options' => [
                                'autocomplete'=>'off'
                            ],
                        ]),
                        'value' => function (Parse $parse) {
                            return $parse->created_at ?? '';
                        }
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>
