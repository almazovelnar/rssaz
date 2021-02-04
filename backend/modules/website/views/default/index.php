<?php

use yii\helpers\Html;
use kartik\date\DatePicker;
use core\helpers\WebsiteHelper;
use backend\widgets\GridViewRemark;
use core\entities\Customer\Customer;
use core\entities\Customer\Website\Website;
use backend\modules\website\grid\WebsiteActionColumn;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\website\models\WebsiteSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Websites';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="website-index">
    <div class="box">
        <div class="box-header with-border">
            <p>
                <?= Html::a('Create Website', ['create'], ['class' => 'btn btn-success']) ?>
            </p>
        </div>
        <div class="box-body">
            <?= GridViewRemark::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    [
                        'attribute' => 'customer',
                        'value' => function (Website $website) {
                            $customer = Customer::find()->andWhere(['id' => $website->customer_id])->one();
                            return $customer ? $customer->getFullName() : null;
                        },
                        'format' => 'raw',
                    ],
                    'name',
                    'address',
                    [
                        'attribute' => 'created_at',
                        'filter' => DatePicker::widget([
                            'model' => $searchModel,
                            'attribute' => 'date_from',
                            'attribute2' => 'date_to',
                            'type' => DatePicker::TYPE_RANGE,
                            'separator' => '-',
                            'pluginOptions' => [
                                'todayHighlight' => true,
                                'autoclose' => true,
                                'format' => 'yyyy-mm-dd'
                            ]
                        ]),
                        'format' => 'datetime'
                    ],
                    [
                        'attribute' => 'status',
                        'filter' => WebsiteHelper::statusesList(),
                        'value' => function(Website $website) {
                            return WebsiteHelper::statusLabel($website->status);
                        },
                        'format' => 'raw',
                    ],

                    ['class' => WebsiteActionColumn::class],
                ],
            ]); ?>
        </div>
    </div>
</div>