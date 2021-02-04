<?php


/* @var $this yii\web\View */
/* @var $page core\entities\Page\Page */
/* @var $model \core\forms\manager\Page\Form */

$this->title = 'Update Page: ' . $page->title;
$this->params['breadcrumbs'][] = ['label' => 'Səhifələr', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $page->title, 'url' => ['view', 'id' => $page->id]];
$this->params['breadcrumbs'][] = 'Update';
?>

<div class="page-update">
    <?= $this->render('_form', ['model' => $model, 'page' => $page]) ?>
</div>
