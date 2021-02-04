<?php


/* @var $this yii\web\View */
/* @var $review \core\entities\Customer\Review\Review */
/* @var $model \core\forms\manager\CustomerReview\Form */

$this->title = 'Update Review';
$this->params['breadcrumbs'][] = ['label' => 'Reviews', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>

<div class="page-update">
    <?= $this->render('_form', ['model' => $model, 'review' => $review]) ?>
</div>
