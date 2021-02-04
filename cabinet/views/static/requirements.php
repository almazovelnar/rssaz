<?php

/**
 * @var \yii\web\View $this
 * @var \core\entities\Page\Page[] $requirements
 * @var \core\entities\Customer\Website\Website[] $websites
 */

$this->title = 'Dokumentasiya';
?>


<section class="documentation-page">
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="white-panel">
                    <ul class="nav nav-tabs" role="tablist">
                        <?php foreach ($requirements->translations as $translation): ?>
                            <?php $language = $translation->language; ?>
                            <?php if ($language != 'en'): ?>
                                <li class="nav-item">
                                    <a class="nav-link <?= ($language == 'az') ?  'active' : '' ?>" href="#<?= $language ?>" data-toggle="tab"><?= strtoupper($language) ?></a>
                                </li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>

                    <div class="tab-content">
                        <?php foreach ($requirements->translations as $translation): ?>
                            <?php $language = $translation->language; ?>
                            <div class="tab-pane <?= ($language == 'az') ?  'active' : ''  ?>" role="tabpanel" id="<?= $language ?>">
                                <div class="editor-body">
                                    <h1><?= $translation->title?></h1>
                                    <?= $translation->description?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <!-- Tab content-->
                </div>
                <!-- White panel-->
            </div>
        </div>
    </div>
</section>