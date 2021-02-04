<?php

use yii\helpers\Html;
use yii\helpers\StringHelper;
use backend\widgets\GridViewRemark;
use backend\widgets\DatePickerRemark;
use core\entities\Customer\Website\{Post, PostRemovalReason};

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel \backend\modules\website\models\PostSearch */

$this->title = 'Removed news';
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
                        'attribute' => 'user_id',
                        'label' => 'Removed By',
                        'value' => function(PostRemovalReason $postRemovalReason){
                            return $postRemovalReason->user->username;
                        },
                        'format' => 'raw'
                    ],
                    [
                        'attribute' => 'website',
                        'value' => function (PostRemovalReason $postRemovalReason) {
                            $post = Post::find()->where(['id' => $postRemovalReason->post_id])->with('website')->one();
                            return Html::a($post->website->name, $post->website->address, ['target' => '_blank']);
                        },
                        'format' => 'raw',
                    ],
                    [
                        'attribute' => 'title',
                        'options' => ['width' => '30%'],
                        'value' => function (PostRemovalReason $postRemovalReason) {
                            $post = Post::find()->where(['id' => $postRemovalReason->post_id])->one();
                            return Html::a(StringHelper::truncate($post->title, 50), $post->link, ['target' => '_blank']);
                        },
                        'format' => 'raw',
                    ],
                    [
                        'attribute' => 'created_at',
                        'label' => 'Removed at',
                        'options' => [
                            'style' => 'width: 230px'
                        ],
                        'filter' => DatePickerRemark::widget([
                            'model' => $searchModel,
                            'attribute' => 'created_at',
                            'pluginOptions' => [
                                'todayHighlight' => true,
                                'autoclose' => true,
                                'format' => 'yyyy-mm-dd'
                            ],
                        ]),
                        'format' => 'datetime'
                    ],
                    'reason',
                    [
                        'class' => 'backend\widgets\ActionColumnRemark',
                        'template' => '{moderate} {delete}',
                        'buttons' => [
                            'moderate' => function ($url) {
                                return Html::a("<span class='md-plus' data-toggle='tooltip' title='Restore news'></span>", $url, [
                                    'class' => "btn btn-warning btn-xs custom_button restore_button",
                                    'data' => ['confirm' => 'Are you sure to restore this news?'],
                                ]);
                            },
                            'delete' => function ($url) {
                                if (Yii::$app->user->can('deleteRecord')){
                                    return Html::a("<span class='md-delete'  data-toggle='tooltip' title='Remove news'></span>", $url, [
                                        'class' => "btn btn-danger btn-xs custom_button",
                                        'data' => ['confirm' => 'Are you sure to remove this news?'],
                                    ]);
                                }
                                return false;
                            }
                        ],
                        'contentOptions' => ['class' => 'table-actions']
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>