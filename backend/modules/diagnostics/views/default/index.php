<?php

use yii\web\View;
use yii\helpers\Html;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use core\entities\Parse\Parse;
use yii\data\ActiveDataProvider;
use backend\widgets\GridViewRemark;
use core\helpers\DiagnosticsHelper;
use core\entities\Customer\Website\Rss;
use core\entities\Customer\Website\Website;
use backend\modules\diagnostics\models\DiagnosticsSearch;

/**
 * @var View $this
 * @var ActiveDataProvider $dataProvider
 * @var DiagnosticsSearch $searchModel
 */

$this->title = 'Diagnostics';
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
                        'attribute' => 'website',
                        'filter' => ArrayHelper::map(Website::find()->where(['!=', 'name', 'rss.az'])->asArray()->all(), 'id', 'name'),
                        'filterInputOptions' => ['prompt' => 'All websites', 'class' => 'form-control'],
                        'value' => function (Parse $parse) {
                            $website = Website::find()->andWhere(['id' => $parse->website_id])->first();
                            if ($website) return Html::a($website->name, $website->address, ['target' => '_blank']);
                            return null;
                        },
                        'format' => 'raw',
                    ],
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
                        'filter' => DiagnosticsHelper::statusesList('success'),
                        'filterInputOptions' => ['prompt' => 'All statuses', 'class' => 'form-control'],
                        'format' => 'raw',
                        'value'  => function (Parse $parse) {
                            return DiagnosticsHelper::statusLabel($parse->status, 'badge badge');
                        },
                    ],
                    [
                        'attribute' => 'created_at',
                        'label' => 'Parsed at',
                        'filter' => DatePicker::widget([
                            'model' => $searchModel,
                            'attribute' => 'date',
                            'type' => DatePicker::TYPE_INPUT,
                            'separator' => '-',
                            'pluginOptions' => [
                                'todayHighlight' => true,
                                'autoclose' => true,
                                'format' => 'yyyy-mm-dd'
                            ]
                        ]),
                    ],
                    [
                        'class' => 'backend\widgets\ActionColumnRemark',
                        'template' => '{view} {delete}',
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>
