<?php

use core\entities\Customer\Customer;
use core\helpers\CustomerHelper;
use kartik\date\DatePicker;
use yii\helpers\Html;
use backend\widgets\GridViewRemark;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\customer\models\CustomerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Customers';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="customer-index">
    <div class="box">
        <div class="box-header with-border">
            <p>
                <?= Html::a('Create Customer', ['create'], ['class' => 'btn btn-success']) ?>
            </p>
        </div>
        <div class="box-body">
            <?= GridViewRemark::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    [
                        'label' => 'Avatar',
                        'value' => function (Customer $customer) {
                            return Html::img(Yii::$app->storage->customer->getThumb(50, $customer->getAvatar()));
                        },
                        'contentOptions' => ['class' => 'avatar-column'],
                        'headerOptions' => ['class' => 'avatar-column'],
                        'format' => 'raw',
                    ],
                    [
                        'value' => function (Customer $customer) {
                            return $customer->getFullName();
                        }
                    ],
                    'email:email',
                    [
                        'attribute' => 'status',
                        'filter' => CustomerHelper::statusesList(),
                        'value' => function(Customer $customer) {
                            return CustomerHelper::statusLabel($customer->status);
                        },
                        'format' => 'raw',
                    ],
                    [
                        'attribute' => 'sites_list',
                        'label' => 'List of websites',
                        'value' => function(Customer $customer) {
                            if ($customer->getSitesList()) {
                                $sites = '';
                                foreach ($customer->getSitesList() as $website) {
                                    $sites .= '<span class="customer-site-span-list">' . $website . '</span>';
                                }
                                return $sites;
                            }
                        },
                        'format' => 'raw',
                    ],
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

                    ['class' => 'backend\widgets\ActionColumnRemark'],
                ],
            ]); ?>
        </div>
    </div>
</div>
