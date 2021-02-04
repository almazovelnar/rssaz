<?php
/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use common\widgets\Alert;
use frontend\assets\AppAsset;
use frontend\widgets\{Header\Header, Footer\Footer};

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->registerCsrfMetaTags() ?>

    <link rel="shortcut icon" href="/images/favicons/favicon.ico" type="image/x-icon">
    <link rel="apple-touch-icon" sizes="144x144" href="/images/favicons/apple-icon-144x144.png">
    <meta name="msapplication-TileColor" content="#e20000">
    <meta name="msapplication-TileImage" content="/images/favicons/apple-icon-140x140.png">
    <link rel="manifest" href="/images/favicons/manifest.json">
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<?= $this->render('/site/partials/google-analytics') ?>
<?= $this->render('/site/partials/push-notifications') ?>

<!-- Yandex.Metrika counter -->
<script type="text/javascript" >
    (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
        m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
    (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");
    ym(55968466, "init", {
        clickmap:true,
        trackLinks:true,
        accurateTrackBounce:true,
        webvisor:true
    });
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/55968466" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<?= Header::widget() ?>

<main class="content">
    <?= $content ?>
</main>

<div class="to-top">
    <i class="icon-angle-up"></i>
</div>

<?= Footer::widget() ?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
