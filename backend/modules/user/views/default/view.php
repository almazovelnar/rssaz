<?php

use yii\helpers\Html;
use core\entities\User;
use yii\widgets\DetailView;
use backend\modules\user\helpers\UserHelper;

/* @var $this yii\web\View */
/* @var $model core\entities\User */

$this->title = ucfirst($model->username);
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="user-view">

   <div class="box">
       <div class="box-header">

       </div>
       <div class="box-body">
           <p>
               <?= Html::a('Düzəliş', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
               <?= Html::a('Şifrəni dəyiş', ['update-password', 'id' => $model->id], ['class' => 'btn btn-warning']) ?>
               <?= Html::a('Sil', ['delete', 'id' => $model->id], [
                   'class' => 'btn btn-danger',
                   'data'  => [
                       'confirm' => 'Bu istifadəçini silməyinizə əminsinizmi ?',
                       'method'  => 'POST',
                   ],
               ]) ?>
           </p>
           <?= DetailView::widget([
               'model' => $model,
               'attributes' => [
                   'id',
                   'username',
                   'email:email',
                   [
                       'attribute' =>'role',
                       'value' => function(User $user) {
                           return UserHelper::roleLabel($user->role);
                       },
                       'format' => 'raw'
                   ],
                   'status',
                   'created_at',
                   'updated_at',
               ],
           ]) ?>
       </div>
   </div>

</div>
