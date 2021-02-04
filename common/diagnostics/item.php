<?php

use yii\web\View;
use core\exceptions\RssParseItemException;

/**
 * @var View $this
 * @var RssParseItemException $error
 * @var SimplePie_Item $item
 */

$item = $error->getItem();
$enclosure = $item->get_enclosure();

?>
<div class="code">
    ...
    <div class="level-1">
        &lt;item&gt;
        <div class="level-2 <?= $error->getField() == 'guid' ? 'error' : '' ?>">
            &lt;guid&gt;<?= $item->get_id() ?>&lt;/guid&gt;
        </div>
        <div class="level-2 <?= $error->getField() == 'title' ? 'error' : '' ?>">
            &lt;title&gt;<?= $item->get_title() ?>&lt;/title&gt;
        </div>
        <div class="level-2 <?= $error->getField() == 'link' ? 'error' : '' ?>">
            &lt;link&gt;<?= $item->get_link() ?>&lt;/link&gt;
        </div>
        <div class="level-2 <?= $error->getField() == 'description' ? 'error' : '' ?>">
            &lt;description&gt;<?= $item->get_description() ?>&lt;/description&gt;
        </div>
        <div class="level-2 <?= $error->getField() == 'pubDate' ? 'error' : '' ?>">
            &lt;pubDate&gt;<?= $item->get_date() ?>&lt;/pubDate&gt;
        </div>
        <div class="level-2 <?= $error->getField() == 'enclosure' ? 'error' : '' ?>">
            &lt;enclosure url="<?= $enclosure->link ?>" type="<?= $enclosure->type ?>"/&gt;
        </div>
        <div class="level-2 <?= $error->getField() == 'category' ? 'error' : '' ?>">
            &lt;category&gt;<?= $item->get_category() ? $item->get_category()->term : '' ?>&lt;/category&gt;
        </div>
        &lt;/item&gt;
    </div>
    ...
</div>
