<?php

use yii\web\View;
use yii\helpers\Url;
use core\entities\Customer\Website\Website;

/**
 * @var View $this
 * @var Website[] $websites
 */

$this->title = 'Saytlarım';
?>
<section class="sites-page">
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="white-panel">
                    <div class="title-links flex">
                        <h2 class="block-title">Saytlarım</h2>
                        <?php if (!empty($websites)): ?>
                        <div class="links flex">
                            <a class="btn-custom" href="<?= Url::to(['code/select-website']) ?>">
                                <?= Yii::t('code', 'generate_code') ?>
                                <i class="material-icons">arrow_forward_ios</i>
                            </a>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php if (!empty($websites)): ?>
                    <div class="sites-list">
                        <?php foreach ($websites as $website): ?>
                        <div class="site flex">
                            <div class="info">
                                <p class="name"><?= $website->name ?></p>
                                <p class="url"><?= $website->address ?></p>
                            </div>

                            <div class="actions">
                                <?php if ($website->isActive()): ?>
                                    <?php if (!$website->isItself()): ?>
                                        <a class="btn-dark" href="<?= Url::to(['diagnostics/index', 'id' => $website->id]) ?>">Diaqnostika<i class="material-icons">settings</i></a>
                                        <a class="btn-dark" href="<?= Url::to(['code/preview', 'id' => $website->id]) ?>">Kod<i class="material-icons">code</i></a>
                                        <a class="btn-dark" href="<?= Url::to(['news/index', 'website_id' => $website->id]) ?>">Xəbərlər<i class="material-icons">library_books</i></a>
                                    <?php endif; ?>
                                    <a class="btn-dark" href="<?= Url::to(['update', 'id' => $website->id]) ?>">Redaktə et<i class="material-icons">edit</i></a>
                                <?php else: ?>
                                    <div class="not-confirmed"><i class="material-icons">announcement</i><?= Yii::t('website', $website->status) ?></div>
                                <?php endif; ?>
                                <a class="btn-delete detele-site"
                                   href="<?= Url::to(['delete', 'id' => $website->id]) ?>"
                                   data-method="POST"
                                   data-confirm="Bu saytı silməyinizə əminsinizmi?"
                                >
                                    <i class="material-icons">highlight_off</i>
                                </a>
                            </div>
                        </div>
                        <!-- Site-->
                        <?php endforeach; ?>
                    </div>
                    <!-- Sites List-->
                    <?php else: ?>
                    <p class="no-items text-center">Siyahı boşdur</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Sites Page-->
