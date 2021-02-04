<?php

use yii\helpers\Html;
use backend\widgets\GridViewRemark;
use backend\widgets\DatePickerRemark;
use backend\modules\website\models\DuplicatedPostSearch;
use core\entities\Customer\Website\{Post, PostDuplicateReason};

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel DuplicatedPostSearch */

$this->title = 'Duplicated news';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="website-index">
    <div class="box">
        <div class="box-body">
            <?= GridViewRemark::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    [
                        'attribute' => 'id',
                        'options' => ['width' => '100px'],
                        'format' => 'raw'
                    ],
                    [
                        'attribute' => 'original_post_id',
                        'label' => 'Original post',
                        'value' => function (PostDuplicateReason $postDuplicateReason){
                            $post = Post::find()->where(['id' => $postDuplicateReason->getOriginalPostId()])->one();
                            return Html::a(Html::img(Yii::$app->storage->post->getThumb(100, $post['image'])), $post->link, ['target' => '_blank', 'title' => $postDuplicateReason->getOriginalPostId()]);
                        },
                        'format' => 'raw'
                    ],
                    [
                        'attribute' => 'duplicated_post_id',
                        'label' => 'Duplicated post',
                        'value' => function (PostDuplicateReason $postDuplicateReason){
                            $post = Post::find()->where(['id' => $postDuplicateReason->getDuplicatedPostId()])->one();
                            return Html::a(Html::img(Yii::$app->storage->post->getThumb(100, $post['image'])), $post->link, ['target' => '_blank', 'title' => $postDuplicateReason->getDuplicatedPostId()]);
                        },
                        'format' => 'raw'
                    ],
                    [
                        'attribute' => 'reason',
                        'options' => ['width' => '30%'],
                        'value' => function (PostDuplicateReason $postDuplicateReason) {
                            return $postDuplicateReason->reason;
                        },
                        'format' => 'raw',
                    ],
                    [
                        'attribute' => 'similarity',
                        'options' => ['width' => '10%'],
                        'value' => function (PostDuplicateReason $postDuplicateReason) {
                            return number_format($postDuplicateReason->similarity, 2) . '%';
                        },
                        'format' => 'raw',
                    ],
                    [
                        'attribute' => 'created_at',
                        'label' => 'Detected at',
                        'options' => [
                            'style' => 'width: 300px'
                        ],
                        'filter' => DatePickerRemark::widget([
                            'model' => $searchModel,
                            'attribute' => 'createdAt',
                            'pluginOptions' => [
                                'todayHighlight' => true,
                                'autoclose' => true,
                                'format' => 'yyyy-mm-dd'
                            ],
                        ]),
                        'format' => 'datetime'
                    ],
                    [
                        'class' => 'backend\widgets\ActionColumnRemark',
                        'template' => '{compare} {activate}',
                        'buttons' => [
                            'activate' => function (string $url, PostDuplicateReason $model) {
                                $route = $url . '&duplicateId=' . $model->getDuplicatedPostId();
                                return Html::a("<span class='md-check' data-toggle='tooltip' title='Activate news'></span>", $route, [
                                    'class' => "btn btn-success btn-xs custom_button",
                                    'data' => ['confirm' => 'Are you sure to activate this news?'],
                                ]);
                            },
                            'compare' => function ($url) {
                                return Html::a("<span class='md-compare' data-toggle='tooltip' title='Compare news'></span>", $url, [
                                    'class' => "btn btn-info btn-xs custom_button",
                                ]);
                            },
                        ],
                        'contentOptions' => ['class' => 'table-actions']
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>