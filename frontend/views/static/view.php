<?php

/**
 * @var \yii\web\View $this
 * @var \core\entities\Page\Page $page
 */

$this->title = $page->multilingual->meta->title;

$this->registerMetaTag(['name' => 'keywords', 'description' => $page->multilingual->meta->keywords]);
$this->registerMetaTag(['name' => 'description', 'description' => $page->multilingual->meta->description]);

?>
<section class="static-page">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2 class="block-title"><?= $page->multilingual->title ?></h2>

                <div class="editor-body">
                    <?= $page->multilingual->description ?>
                </div>
            </div>
        </div>
    </div>
</section>
