<?php

/**
 * @var \yii\web\View $this
 * @var \core\entities\Customer\Website\Rss $rss
 */

use yii\helpers\Html;

?>
Item error(s) detected in <?= Html::a(Html::encode($rss->rss_address), $rss->rss_address) ?>

Visit your cabinet for more information

Check <?= Yii::$app->cabinetUrlManager->createAbsoluteUrl(['diagnostics/index', 'id' => $rss->website_id, 'rss_id' => $rss->id]) ?> to view errors