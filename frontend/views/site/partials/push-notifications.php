<?php
$appIds = [
    'az' => 'c4344396-7589-4660-ae8f-5b64065d0201',
    'ru' => 'cc639cca-8d7c-4142-b557-17db8d67e7b7',
    'en' => 'c4ff8d54-c9c6-446e-9afa-17919cf9ce9c',
];
$appId = $appIds[Yii::$app->language] ?? $appIds[Yii::$app->params['defaultLanguage']];
?>
<script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async=""></script>
<script>
    window.OneSignal = window.OneSignal || [];
    OneSignal.push(function() {
        OneSignal.init({
            appId: "<?= $appId ?>",
        });
    });
</script>
