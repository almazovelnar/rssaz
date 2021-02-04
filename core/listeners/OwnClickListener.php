<?php

namespace core\listeners;

use Yii;
use Exception;
use core\events\OwnClick;
use core\entities\Statistics\Raw;
use core\repositories\{interfaces\PostRepositoryInterface, interfaces\WebsiteRepositoryInterface,};

/**
 * Class OwnClickListener
 * @package core\listeners
 */
class OwnClickListener
{
    private WebsiteRepositoryInterface $websiteRepository;
    private PostRepositoryInterface $postRepository;

    public function __construct(
        WebsiteRepositoryInterface $websiteRepository,
        PostRepositoryInterface $postRepository
    )
    {
        $this->websiteRepository = $websiteRepository;
        $this->postRepository = $postRepository;
    }

    public function handle(OwnClick $click)
    {
        $post = $click->getPost();
        try {
            $website = $this->websiteRepository->getAggregator();
            $referrerType = (strpos($click->getReferrer(), 'redirect') !== false)
                ? Raw::REFERRER_TYPE_REDIRECT
                : Raw::REFERRER_TYPE_SITE;
            Raw::create($website->getId(), $post->website_id, $post->getId(), Raw::TYPE_CLICK, $referrerType, 'aggregator');
            $this->postRepository->increment('clicks', $post);
            $this->postRepository->calculateCtr($post);
        } catch (Exception $e) {
            Yii::$app->errorHandler->logException($e);
        }
    }
}
