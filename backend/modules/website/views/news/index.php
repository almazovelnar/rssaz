<?php

use yii\helpers\Html;
use kartik\date\DatePicker;
use backend\widgets\GridViewRemark;
use yii\helpers\{Url, StringHelper};
use backend\modules\website\models\PostSearch;
use core\entities\Customer\Website\{Website, Post};

/* @var $this yii\web\View */
/* @var $searchModel PostSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'News';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="website-index">
    <div class="box">
        <div class="box-header">
            <div class="reset-filters">
                <a
                  href="<?= Url::to(['reset-filters']) ?>"
                  class="btn btn-outline-success"
                  onclick="confirm('Are you sure to reset filters ?')">
                    Reset filters
                </a>
            </div>
            <br>
        </div>
        <div class="box-body">
            <?= GridViewRemark::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'attribute' => 'image',
                        'value' => function (Post $post) {
                            return Html::img(Yii::$app->storage->post->getThumb(100, $post['image']));
                        },
                        'format' => 'raw',
                    ],
                    [
                        'attribute' => 'website',
                        'value' => function (Post $post) {
                            $website = Website::find()->andWhere(['id' => $post->website_id])->first();
                            return ($website !== null)
                                ? Html::a($website->name, $website->address, ['target' => '_blank'])
                                : 'website-not-found';
                        },
                        'format' => 'raw',
                    ],
                    [
                        'attribute' => 'title',
                        'options' => ['width' => '30%'],
                        'value' => function (Post $post) {
                            return Html::a(StringHelper::truncate($post->title, 50), $post->link, ['target' => '_blank']);
                        },
                        'format' => 'raw',
                    ],
                    [
                        'label' => 'Language',
                        'attribute' => 'lang',
                        'filter' => Yii::$app->params['languages'],
                        'value' => function (Post $post) {
                            return Yii::$app->params['languages'][$post->lang];
                        }
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
                    [
                        'attribute' => 'views',
                        'options' => ['class' => 'min-chars-column']
                    ],
                    [
                        'attribute' => 'clicks',
                        'options' => ['class' => 'min-chars-column']
                    ],
                    [
                        'attribute' => 'priority',
                        'contentOptions' => ['class' => 'min-chars-column']
                    ],
                    [
                        'class' => 'backend\widgets\ActionColumnRemark',
                        'template' => '{prioritize} {moderate} {delete}',
                        'buttons' => [
                            'prioritize'=> function ($url, Post $post) use ($request) {
                                $type = 'prioritize';
                                $classes = ['thumb' => 'up', 'btn' => 'success'];

                                if ($post->prioritized()) {
                                    $type = 'de-prioritize';
                                    $classes = ['thumb' => 'down', 'btn' => 'primary'];
                                }
                                $route = Url::to([$type, 'id' => $post->id, 'page' => $request->get('page')]);

                                return Html::a("<span class='md-thumb-{$classes['thumb']}'></span>", $route, [
                                    'class' => "btn btn-{$classes['btn']} btn-xs custom_button",
                                    'data' => ['confirm' => 'Are you sure to prioritize this news?'],
                                ]);
                            },
                            'moderate' => function ($url) {
                                if (!Yii::$app->user->can('deleteRecord')){
                                    return Html::a("<span class='md-delete' data-toggle='tooltip' title='Remove news'></span>", $url, [
                                        'class' => "btn btn-danger btn-xs custom_button remove_button",
                                    ]);
                                }else{
                                    return Html::a("<span class='md-minus' data-toggle='tooltip' title='Moderate news'></span>", $url, [
                                        'class' => "btn btn-warning btn-xs custom_button remove_button",
                                    ]);
                                }
                            },
                            'delete' => function ($url) {
                                if (Yii::$app->user->can('deleteRecord')){
                                    return Html::a("<span class='md-delete' data-toggle='tooltip' title='Remove news'></span>", $url, [
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