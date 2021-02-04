<?php

use core\entities\User;
use core\helpers\CommonHelper;
use kartik\date\DatePicker;
use yii\helpers\Html;
use backend\widgets\GridViewRemark;
use backend\modules\user\helpers\UserHelper;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\user\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">
   <div class="box">
      <div class="box-header with-border">
          <p><?= Html::a('Create user', ['create'], ['class' => 'btn btn-success']) ?></p>
      </div>
       <div class="box-body">
           <?= GridViewRemark::widget([
               'dataProvider' => $dataProvider,
               'filterModel' => $searchModel,
               'columns' => [
                   ['class' => 'yii\grid\SerialColumn'],
                   'username',
                   'email:email',
                   [
                       'attribute' => 'role',
                       'filter' => UserHelper::rolesList(),
                       'value' => function(User $user) {
                           return UserHelper::roleLabel($user->role);
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
                   [
                       'attribute' => 'status',
                       'filter' => CommonHelper::statusesList(),
                       'value' => function(User $user) {
                           return CommonHelper::statusLabel($user->status);
                       },
                       'format' => 'raw',
                   ],
                   ['class' => 'backend\widgets\ActionColumnRemark'],
               ],
           ]); ?>
       </div>
   </div>
</div>
