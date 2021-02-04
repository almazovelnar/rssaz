<?php

namespace core\listeners;

use Yii;
use Exception;
use core\events\Click;
use core\entities\Statistics\Raw;
use core\entities\Session\Click as SessionClick;
use core\repositories\interfaces\{SessionRepositoryInterface, PostRepositoryInterface, WebsiteRepositoryInterface};

/**
 * Class ClickListener
 * @package core\listeners
 */
class ClickListener
{
    private SessionRepositoryInterface $sessionRepository;
    private PostRepositoryInterface $postRepository;
    private WebsiteRepositoryInterface $websiteRepository;

    public function __construct(
        SessionRepositoryInterface $sessionRepository,
        PostRepositoryInterface $postRepository,
        WebsiteRepositoryInterface $websiteRepository
    )
    {
        $this->sessionRepository = $sessionRepository;
        $this->postRepository = $postRepository;
        $this->websiteRepository = $websiteRepository;
    }

    public function handle(Click $click)
    {
        $form = $click->getForm();
        $post = $click->getPost();

        if (($session = $this->sessionRepository->postExistsInSession($post->getId(), $form->sid)) === null) return false;

        try {
            if ($this->sessionRepository->getClickCountForIP($click->getIp(), $post->getId()) === 0) {
                Raw::create(
                    $session->website_id,
                    $post->website_id,
                    $post->getId(),
                    Raw::TYPE_CLICK,
                    Raw::REFERRER_TYPE_BANNERS,
                    $session->algorithm
                );

                $this->postRepository->increment('clicks', $post);
                $this->postRepository->calculateCtr($post);
            }

            SessionClick::create($session->getId(), $post->getId());
        } catch (Exception $e) {
            Yii::$app->errorHandler->logException($e);
        }
    }
}