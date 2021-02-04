<?php

/**
 * @var \yii\web\View $this
 * @var \core\entities\Customer\Website\Rss $rss
 */

use yii\helpers\Html;

?>
<div class="rss-error">
    <h1 class="title">Error(s) detected in <?= Html::a(Html::encode($rss->rss_address), $rss->rss_address) ?></h1>

    <p>Visit your cabinet for more information</p>

    <a href="<?= Yii::$app->cabinetUrlManager->createAbsoluteUrl(['diagnostics/index', 'id' => $rss->website_id, 'rss_id' => $rss->id]) ?>" class="btn-custom">
        View errors
    </a>
</div>
