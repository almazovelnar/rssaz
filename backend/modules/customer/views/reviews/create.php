<?php

/* @var $this yii\web\View */
/* @var $model \core\entities\Customer\Review\Review */

$this->title = 'New Review';
$this->params['breadcrumbs'][] = ['label' => 'Reviews', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="page-create">
    <?= $this->render('_form', ['model' => $model]) ?>
</div>
