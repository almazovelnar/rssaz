<?php
    $identityMap = ["az" => 1, "ru" => 2, "en" => 3];
    $identity = $identityMap[Yii::$app->language] ?? 1;
?>
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-150207439-<?= $identity ?>"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'UA-150207439-<?= $identity ?>');
</script>