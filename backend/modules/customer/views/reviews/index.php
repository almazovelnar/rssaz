<?php

use yii\helpers\Html;
use core\helpers\CommonHelper;
use backend\widgets\GridViewRemark;
use core\entities\Customer\Review\Review;
use core\entities\Customer\Website\Website;
use backend\modules\customer\models\ReviewSearch;

/* @var $this yii\web\View */
/* @var $searchModel ReviewSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Reviews';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-index">
    <div class="box">
        <div class="box-header with-border">
            <p><?= Html::a('Create Review', ['create'], ['class' => 'btn btn-success']) ?></p>
        </div>
        <div class="box-body">
            <?= GridViewRemark::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'label' => 'Website',
                        'attribute' => 'website_id',
                        'value' => function(Review $review) {
                            return Website::findOne($review->website_id)->getName();
                        },
                        'format' => 'raw',
                    ],
                    'created_at',
                    [
                        'attribute' => 'status',
                        'filter' => CommonHelper::statusesList(),
                        'value' => function(Review $review) {
                            return CommonHelper::statusLabel($review->status);
                        },
                        'format' => 'raw',
                    ],
                    [
                        'class' => 'backend\widgets\ActionColumnRemark',
                        'template' => '{update} {delete}',
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>
