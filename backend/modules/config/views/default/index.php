<?php

use backend\widgets\GridViewRemark;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\config\models\ConfigSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Configuration';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="config-index">
  <div class="box box-primary">
      <div class="box-body">
          <?= GridViewRemark::widget([
              'dataProvider' => $dataProvider,
              'filterModel' => $searchModel,
              'columns' => [
                  ['class' => 'yii\grid\SerialColumn'],

                  'param',
                  'value:ntext',
                  'default:ntext',
                  'label',
                  [
                    'class' => 'backend\widgets\ActionColumnRemark',
                    'template' => '{update}',
                  ],
              ],
          ]); ?>
      </div>
  </div>
</div>
