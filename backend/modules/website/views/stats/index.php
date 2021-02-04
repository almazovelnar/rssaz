<?php

use yii\helpers\Html;
use yii\data\ActiveDataProvider;
use backend\widgets\GridViewRemark;
use Tightenco\Collect\Support\Collection;
use core\entities\Customer\Website\Website;
use backend\modules\website\models\WebsiteStatsSearch;

$this->title = "Websites' stats";
$this->params['breadcrumbs'][] = $this->title;

/** @var ActiveDataProvider $dataProvider */
/** @var WebsiteStatsSearch $searchModel */
/** @var Collection $stats */
?>
<div class="website-index">
    <div class="box">
        <div class="box-header with-border">
           <p><?= $this->title ?></p>
        </div>
        <div class="box-body">
            <?= GridViewRemark::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'attribute' => 'website',
                        'filter' => $searchModel->getWebsites(),
                        'filterInputOptions' => ['prompt' => 'All websites', 'class' => 'form-control'],
                        'value' => fn (Website $website) => Html::a($website->getName(), $website->getAddress(), ['target' => '_blank']),
                        'format' => 'raw',
                    ],
                    [
                        'attribute' => 'inClicks',
                        'options' => ['class' => 'min-chars-column'],
                        'value' => fn(Website $website) => $stats->has($website->getId()) ? (int) $stats->get($website->getId())['in_clicks'] : 0,
                    ],
                    [
                        'attribute' => 'outClicks',
                        'options' => ['class' => 'min-chars-column'],
                        'value' => fn(Website $website) => $stats->has($website->getId()) ? (int) $stats->get($website->getId())['out_clicks'] : 0,
                    ],
                    [
                        'attribute' => 'rate',
                        'contentOptions' => ['class' => 'min-chars-column'],
                        'value' => fn(Website $website) => $stats->has($website->getId()) ? $stats->get($website->getId())['rate_actual'] : 0.0,
                    ],
                    [
                        'attribute' => 'rate_min',
                        'label' => 'Default Rate',
                        'contentOptions' => ['class' => 'min-chars-column'],
                        'value' => fn(Website $website) => $website->rate_min,
                    ],
                    [
                        'attribute' => 'ratedAt',
                        'contentOptions' => ['class' => 'min-chars-column'],
                        'value' => fn(Website $website) => $stats->has($website->getId()) ? $stats->get($website->getId())['rated_at'] : 0.0,
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>