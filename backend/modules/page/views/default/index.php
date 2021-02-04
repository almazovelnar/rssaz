<?php

use core\helpers\CommonHelper;
use yii\helpers\Html;
use backend\widgets\GridViewRemark;
use core\entities\Page\Page;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\page\models\PageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Statik səhifələr';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-index">
    <div class="box">
        <div class="box-header with-border">
            <p><?= Html::a('Yeni səhifə', ['create'], ['class' => 'btn btn-success']) ?></p>
        </div>
        <div class="box-body">
            <?= GridViewRemark::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'title',
                    [
                        'attribute' => 'status',
                        'filter' => CommonHelper::statusesList(),
                        'value' => function(Page $page) {
                            return CommonHelper::statusLabel($page->status);
                        },
                        'format' => 'raw',
                    ],
                    'created_at',
                    [
                        'class' => 'backend\widgets\ActionColumnRemark',
                        'template' => '{update} {delete}',
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>
