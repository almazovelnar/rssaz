<?php

/* @var $this yii\web\View */
/* @var $model \core\forms\manager\Category\Form */
/* @var $category \core\entities\Category\Category */

$this->title = 'Update Category: ' . $category->title;
$this->params['breadcrumbs'][] = ['label' => 'Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $category->title, 'url' => ['view', 'id' => $category->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="category-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
