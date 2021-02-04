<?php

use core\helpers\CustomerHelper;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model core\entities\Customer\Customer */

$this->title = $model->getFullName();
$this->params['breadcrumbs'][] = ['label' => 'Customers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="customer-view">
    <div class="row">
        <div class="col-lg-4">
            <div class="box box-info">
                <div class="box-body box-profile">
                    <div class="user-info flex">
                        <div class="thumb">
                            <img src="<?= Yii::$app->storage->customer->getThumb(50, $model->getAvatar()) ?>" alt="Customer" class="profile-user-img img-responsive">
                        </div>
                        <div class="info">
                            <h3 class="profile-username"><?= $model->getFullName() ?></h3>
                            <p class="text-muted"><?= $model->email ?></p>
                        </div>
                    </div>
                    <ul class="list-group list-group-unbordered">
                        <li class="list-group-item">
                            <b>Member since</b>
                            <span class="pull-right"><?= $model->created_at ?></span>
                        </li>
                        <li class="list-group-item">
                            <b>Status</b>
                            <span class="pull-right"><?= CustomerHelper::statusLabel($model->status) ?></span>
                        </li>
                        <li class="list-group-item">
                            <b>List of websites:</b><br>
                            <?php if ($model->getSitesList()): ?>
                                <?php foreach ($model->getSitesList() as $website): ?>
                                    <span class="customer-site-span"><?= $website ?></span>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </li>
                    </ul>
                    <div class="btn-group btn-group-justified customer-buttons">
                        <div class="btn-group">
                            <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                        </div>
                        <div class="btn-group">
                            <?= Html::a('Change password', ['update-password', 'id' => $model->id], ['class' => 'btn btn-warning']) ?>
                        </div>
                        <div class="btn-group">
                            <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                                'class' => 'btn btn-danger',
                                'data' => [
                                    'confirm' => 'Are you sure you want to delete this item?',
                                    'method' => 'post',
                                ],
                            ]) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
