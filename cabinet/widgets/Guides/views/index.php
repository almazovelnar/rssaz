<?php

/** @var \core\entities\Category\Category[] $categories */

use yii\helpers\Url;
?>

<?php if ($pages): ?>
<div class="guide-links">
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="white-panel">
                    <ul class="flex">
                        <?php foreach ($pages as $page):?>
                            <li>
                                <a href="<?= Url::to(['/' . $page->slug])?>"><?= $page->title ?></a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>