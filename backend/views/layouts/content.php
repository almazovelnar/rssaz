<?php

use yii\bootstrap\Alert;
use yii\helpers\Html;
use yii\helpers\Inflector;
use yii\widgets\Breadcrumbs;

/**
 * @var string $content
 */
?>

<div class="page">

    <?php if (in_array($this->context->action->id, ['create', 'update', 'view'])): ?>
        <div class="page-header">
            <?php if (isset($this->blocks['content-header'])) { ?>
                <h1 class="page-title"><?= $this->blocks['content-header'] ?></h1>
            <?php } else { ?>
                <h1 class="page-title">
                    <?php
                    if ($this->title !== null) {
                        echo Html::encode($this->title);
                    } else {
                        echo Inflector::camel2words(
                            Inflector::id2camel($this->context->module->id)
                        );
                        echo ($this->context->module->id !== \Yii::$app->id) ? '<small>Module</small>' : '';
                    } ?>
                </h1>
            <?php } ?>
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                'itemTemplate' => "<li>{link}&nbsp;/&nbsp;</li>"
            ]) ?>
        </div>
    <?php endif; ?>
    <div class="page-content container-fluid">
        <div class="panel">
            <div class="panel-body container-fluid">
                <?= $content ?>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="site-footer">
    <div class="site-footer-legal">Copyright © <a href="https://rss.az" target="_blank">Rss.az</a></div>
</footer>