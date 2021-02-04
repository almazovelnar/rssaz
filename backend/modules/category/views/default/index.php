<?php

use core\entities\Category\Category;
use core\helpers\CommonHelper;
use backend\widgets\GridViewRemark;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel \backend\modules\category\models\CategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Categories';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-index">

    <div class="box">
        <div class="box-header with-border">
            <p>
                <?= Html::a('Create Category', ['create'], ['class' => 'btn btn-success']) ?>
            </p>
        </div>
        <div class="box-body">
            <?= GridViewRemark::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    [
                        'label' => 'Sort',
                        'value' => function (Category $model) {
                            if ($model->isRoot()) {
                                return '';
                            }
                            return
                                Html::a('<i class="fa fa-angle-up"></i>', ['move-up', 'id' => $model->id], ['data-method' => 'post'])
                                . '&nbsp;&nbsp;&nbsp;&nbsp;'
                                . Html::a('<i class="fa fa-angle-down"></i>', ['move-down', 'id' => $model->id], ['data-method' => 'post']);
                        },
                        'format' => 'raw',
                        'contentOptions' => ['class' => 'sort-column'],
                    ],
                    [
                        'attribute' => 'name',
                        'value' => function (Category $model) {
                            if ($model->isRoot()) {
                                return Html::tag('span', $model->title);
                            }
                            $indent = ($model->depth > 1 ? str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', $model->depth) . ' ' : '');
                            return $indent . Html::encode($model->title);
                        },
                        'format' => 'raw',
                    ],
                    [
                        'attribute' => 'status',
                        'filter' => CommonHelper::statusesList(),
                        'value' => function(Category $category) {
                            return CommonHelper::statusLabel($category->status);
                        },
                        'format' => 'raw',
                    ],
                    [
                        'attribute' => 'show_in_menu',
                        'filter' => CommonHelper::statusesList(),
                        'value' => function(Category $category) {
                            return CommonHelper::statusLabel($category->show_in_menu);
                        },
                        'format' => 'raw',
                    ],

                    ['class' => 'backend\widgets\ActionColumnRemark'],
                ],
            ]); ?>
        </div>
    </div>

</div>