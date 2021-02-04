<?php

use yii\web\View;
use yii\helpers\Html;
use core\entities\Parse\Parse;
use yii\data\ActiveDataProvider;
use backend\widgets\GridViewRemark;
use core\helpers\DiagnosticsHelper;
use core\entities\Customer\Website\{Website, Rss};
use backend\modules\diagnostics\models\DiagnosticsSearch;

/**
 * @var View $this
 * @var ActiveDataProvider $dataProvider
 * @var DiagnosticsSearch $searchModel
 * @var Website $website
 * @var string $status
 */

$this->title = $website->name;
?>

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
                        'attribute' => 'rss',
                        'value' => function (Parse $parse) {
                            $rss = Rss::find()->andWhere(['id' => $parse->rss_id])->first();
                            if ($rss) return Html::a($rss->rss_address, $rss->rss_address, ['target' => '_blank']);
                            return null;
                        },
                        'format' => 'raw',
                    ],
                    [
                        'attribute' => 'status',
                        'filter' => false,
                        'value'  => function (Parse $parse) {
                            return DiagnosticsHelper::statusLabel($parse->status);
                        },
                        'format' => 'raw'
                    ],
                    [
                        'label' => 'Correct ones/Total pages',
                        'value'  => function (Parse $parse) {
                            return  $parse->news_count . '/' . $parse->getTotal();
                        },
                    ],
                    [
                        'attribute' => 'elapsed_time',
                        'filter' => false,
                    ],
                    [
                        'attribute' => 'created_at',
                        'label' => 'Parsed at',
                        'filter' => false,
                    ],
                    [
                        'class' => 'backend\widgets\ActionColumnRemark',
                        'template' => '{view} {delete}',
                        'buttons' => [
                            'view' => function ($url, $options) {
                                return Html::a("<span class='md-eye'></span>", ['/diagnostics/default/view', 'id' => $options->id]);
                            },
                            'delete' => function ($url, $options) {
                                return Html::a("<span class='md-delete'></span>", ['/statistics/default/delete', 'id' => $options->id, 'website' => $options->website_id, 'status' => $options->status],[
                                    'data' => ['confirm' => 'Are you sure to remove this item?'],
                                ]);
                            },
                        ],
                    ]
                ],
            ]); ?>
        </div>
    </div>
</div>
