<?php

use yii\bootstrap\Alert;
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */

backend\assets\AppAsset::register($this);
$this->registerJsFile('@web/remark/global/vendor/breakpoints/breakpoints.js', ['position' => \yii\web\View::POS_HEAD]);
$this->registerJs('Breakpoints();');;
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="page-login-v2 layout-full page-dark">

<?php $this->beginBody() ?>
    <?= Alert::widget() ?>
    <?= $content ?>
<?php $this->endBody() ?>
<script src="/remark/global/js/config/colors.js"></script>
<script>Config.set('assets', '../assets');</script>
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
