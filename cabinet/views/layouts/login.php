<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use cabinet\assets\AppAsset;
use common\widgets\Alert;

AppAsset::register($this);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<main class="content">
    <?= Alert::widget() ?>
    <?= $content ?>
</main>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
