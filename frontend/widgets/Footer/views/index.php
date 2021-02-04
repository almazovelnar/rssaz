<?php
/** @var array $pages */
use yii\helpers\Url;
?>
<footer>
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="inner flex">
                    <div class="copyrights">
                        <p>RSS.az</p>
                        <p>Copyright © <?= date('Y') ?> <?= Yii::t('main', 'rights_reserved') ?></p>
                        <ul class="socials">
                            <li><a href="<?= Yii::$app->config->get('facebook_page') ?>" target="_blank"><i class="icon-facebook"></i></a></li>
                            <li><a href="<?= Yii::$app->config->get('twitter_page') ?>" target="_blank"><i class="icon-twitter"></i></a></li>
                            <li><a href="<?= Yii::$app->config->get('linkedin_page') ?>" target="_blank"><i class="icon-linkedin"></i></a></li>
                            <li><a href="<?= Yii::$app->config->get('facebook_page') ?>" target="_blank"><i class="icon-youtube-play"></i></a></li>
                        </ul>
                    </div>


                    <div class="reference">
                        <ul class="static-links">
                            <li>
                                <a href="<?= Url::to(['/reviews'])?>"><?= Yii::t("main",'reviews') ?></a>
                            </li>
                            <?php foreach ($pages as $page): ?>
                                <li>
                                    <a href="<?= Url::to(['/' . $page->slug])?>"><?= $page->title ?></a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <p><?= Yii::t('main', 'new_reference') ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>