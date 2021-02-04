<?php

/**
 * @var View $this
 * @var Review[] $reviews
 */

use yii\web\View;
use frontend\helpers\WebsiteHelper;
use core\entities\Customer\Review\Review;
use core\entities\Customer\Website\Website;

$this->title = Yii::t("main",'reviews');

?>

<section class="reviews-page">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2 class="section-title">
                    <span><?= $this->title ?></span>
                </h2>

                <div class="reviews-list">
                    <div class="row">

                        <?php
                            foreach ($reviews as $review):
                                $website = $review['website'];
                                /** @var Website $website */
                            ?>
                            <div class="col-lg-4 col-md-6">
                                <div class="review-block flex">
                                    <div class="review">
                                        <p><?= $review['multilingual']['review'] ?></p>
                                    </div>
                                    <div class="reviewer flex">
                                        <a class="source" href="<?= $website->getAddress() ?>" target="_blank">
                                            <img src="<?= WebsiteHelper::getIcon($website->getName()) ?>">
                                            <span><?= $website->getName() ?></span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <!-- Col-->
                        <?php endforeach; ?>

                    </div>
                </div>
                <!-- Reviews list-->
            </div>
        </div>
    </div>
</section>
<!-- Reviews page-->