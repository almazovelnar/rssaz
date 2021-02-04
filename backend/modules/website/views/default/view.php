<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use core\helpers\WebsiteHelper;
use core\entities\Customer\Website\Website;

/* @var $this yii\web\View */
/* @var $model Website */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Websites', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="website-view">

    <div class="box box-info">
        <div class="box-header with-border">
            <p>
                <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Are you sure you want to delete this item?',
                        'method' => 'post',
                    ],
                ]) ?>
            </p>
        </div>
        <div class="box-body">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'id',
                    [
                        'label' => 'Customer',
                        'value' => function (Website $website) {
                            return $website->customer->getFullName();
                        }
                    ],
                    'name',
                    'traffic_limit',
                    [
                        'attribute' => 'address',
                        'value' => function (Website $website) {
                            return Html::a(Html::encode($website->address), $website->address, ['target' => '_blank']);
                        },
                        'format' => 'raw',
                    ],
                    [
                        'attribute' => 'update_frequency',
                        'format' => 'raw',
                    ],
                    'created_at',
                    [
                        'attribute' => 'status',
                        'value' => function (Website $website) {
                            return WebsiteHelper::statusLabel($website->status);
                        },
                        'format' => 'raw',
                    ],
                ],
            ]) ?>
        </div>
    </div>

</div>
