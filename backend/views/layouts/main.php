<?php
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */

backend\assets\AppAsset::register($this);
$this->registerJsFile('@web/remark/global/vendor/breakpoints/breakpoints.js', ['position' => \yii\web\View::POS_HEAD]);
$this->registerJs('Breakpoints();');
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>"  class="no-js css-menubar">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="dashboard site-menubar-keep site-menubar-unfold">

    <script>
        var navIsFolded = localStorage.getItem("navFolded");
        var bodyEl = document.querySelector("body");

        if (navIsFolded !== null) {
            bodyEl.classList.add("site-menubar-fold");
        }
    </script>

    <?php $this->beginBody() ?>

        <?= $this->render('header.php') ?>

        <?= $this->render('left.php') ?>

        <?= $this->render('content.php', ['content' => $content]) ?>

    <?php $this->endBody() ?>
    <script src="/remark/assets/js/Site.js"></script>
    <script>
        (function(document, window, $){
            'use strict';

            var Site = window.Site;
            $(document).ready(function(){
                Site.run();
            });
        })(document, window, jQuery);
    </script>

</body>
</html>
<?php $this->endPage() ?>