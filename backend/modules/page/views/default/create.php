<?php

/* @var $this yii\web\View */
/* @var $model core\entities\Page\Page */

$this->title = 'Yeni səhifə';
$this->params['breadcrumbs'][] = ['label' => 'Səhifələr', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="page-create">
    <?= $this->render('_form', ['model' => $model]) ?>
</div>
