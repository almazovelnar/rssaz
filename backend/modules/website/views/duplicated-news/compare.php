<?php

use core\entities\Customer\Website\Post;
use yii\helpers\Url;

$this->title = 'Duplicated news';
$this->params['breadcrumbs'][] = $this->title;

/** @var Post $original */
/** @var Post $duplicated */
?>

<div class="website-index">
    <div class="box">
        <a class="btn btn-success" href="<?= Url::to(['index']) ?>">Back</a>
        <div class="box-body">
            <br>
           <div class="row">
               <div class="col-md-6">
                   <h2>Original post:</h2>
                   <ul class="list-group">
                       <li class="list-group-item">Id: <?= $original->getId() ?></li>
                       <li class="list-group-item">Link to actual post:
                           <br>
                           <a target="_blank" href="<?= $original->link ?>"><?= $original->link ?></a></li>
                       <li class="list-group-item">Title: <?= $original->getTitle() ?></li>
                       <li class="list-group-item">Image (<?= $original->image ?>):
                           <br>
                           <img src="<?= Yii::$app->storage->post->getThumb(375, $original->image) ?>" alt="">
                       </li>
                   </ul>
               </div>
               <div class="col-md-6">
                   <h2>Duplicated post:</h2>
                   <ul class="list-group">
                       <li class="list-group-item">Id: <?= $duplicated->getId() ?></li>
                       <li class="list-group-item">Link to duplicated post:
                           <br>
                           <a target="_blank" href="<?= $duplicated->link ?>"><?= $duplicated->link ?></a></li>
                       <li class="list-group-item">Title: <?= $duplicated->getTitle() ?></li>
                       <li class="list-group-item">Image (<?= $duplicated->image ?>):
                           <br>
                           <img src="<?= Yii::$app->storage->post->getThumb(375, $duplicated->image) ?>" alt="">
                       </li>
                   </ul>
               </div>
           </div>
        </div>
    </div>
</div>
