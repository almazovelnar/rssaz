<?php

use yii\helpers\Url;
use frontend\models\FilterForm;

/** @var string $defaultPeriod */
$cookies = Yii::$app->request->cookies;
$getPeriod = $cookies->getValue('filter_period') ?? $defaultPeriod;
?>
<div class="news-filters hidden-767">
    <div class="container">
        <div class="row">
            <div class="col-12">

                <form method="GET" data-url="<?= Url::to(['/site/filter']) ?>">
                    <ul>
                        <?php foreach (FilterForm::getPeriods() as $period => $label): ?>
                           <li>
                               <input <?= ($period == $getPeriod) ? 'checked' : null; ?>
                                   type="radio"
                                   value="<?= $period ?>"
                                   name="period"
                                   id="period-<?= $period ?>"
                                   class="form-control period-filter">
                               <label for="period-<?= $period ?>"><?= $label ?></label>
                           </li>
                        <?php endforeach; ?>
                    </ul>
                </form>

                <div class="search-field">
                    <div class="form-group">
                        <input class="form-control search" type="text" placeholder="<?= Yii::t('main', 'search') ?>">
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<!-- News filters-->

<div class="news-filters mobile-filters visible-767">
    <div class="container">
        <div class="row">
            <div class="col-12">

                <form method="GET" data-url="<?= Url::to(['/site/filter']) ?>">
                    <div class="form-group select">
                        <select class="form-control period-filter" name="period">
                            <?php foreach (FilterForm::getPeriods() as $period => $label): ?>
                                <option <?= ($period == $getPeriod) ? 'selected' : null ?> value="<?= $period ?>"><?= $label ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </form>

                <div class="search-field">
                    <div class="form-group">
                        <input class="form-control search" type="text" placeholder="<?= Yii::t('main', 'search') ?>">
                    </div>

                    <div class="open-search">
                        <i class="icon-search"></i>
                    </div>

                    <div class="close-search">
                        <i class="icon-close"></i>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
