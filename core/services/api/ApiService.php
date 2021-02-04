<?php

namespace core\services\api;

use Exception;
use yii\web\Request;
use core\events\View;
use core\helpers\ApiHelper;
use core\entities\Session\Session;
use core\dispatchers\EventDispatcher;
use core\services\cache\RedisService;
use core\exceptions\NotFoundCachedException;
use core\repositories\WebsiteRedisRepository;
use core\algorithms\manager\AlgorithmManager;
use core\responses\{ResponseDispatcher, ApiResponse};
use core\repositories\interfaces\{SessionRepositoryInterface, PostRepositoryInterface, WebsiteRepositoryInterface};

/**
 * Class ApiService
 * @package core\services\api
 * @author Vagif Rufullazada
 */
class ApiService
{
    private WebsiteRedisRepository $websiteRedisRepository;
    private WebsiteRepositoryInterface $websiteRepository;
    private SessionRepositoryInterface $sessionRepository;
    private PostRepositoryInterface $postRepository;
    private EventDispatcher $eventDispatcher;
    private ResponseDispatcher $responseDispatcher;
    private AlgorithmManager $algorithmManager;
    private RedisService $redisService;

    public function __construct(
        WebsiteRedisRepository $websiteRedisRepository,
        WebsiteRepositoryInterface $websiteRepository,
        SessionRepositoryInterface $sessionRepository,
        PostRepositoryInterface $postRepository,
        EventDispatcher $eventDispatcher,
        ResponseDispatcher $responseDispatcher,
        AlgorithmManager $algorithmManager,
        RedisService $redisService
    )
    {
        $this->websiteRedisRepository = $websiteRedisRepository;
        $this->websiteRepository = $websiteRepository;
        $this->sessionRepository = $sessionRepository;
        $this->postRepository = $postRepository;
        $this->eventDispatcher = $eventDispatcher;
        $this->responseDispatcher = $responseDispatcher;
        $this->algorithmManager = $algorithmManager;
        $this->redisService = $redisService;
    }

    /**
     * @param string $hash
     * @param Request $request
     * @return string
     * @throws Exception
     */
    public function getBlocks(string $hash, Request $request): string
    {
        try {
            $website = $this->websiteRedisRepository->getWebsiteByHash($hash);
        } catch (NotFoundCachedException $e) {
            $website = $this->websiteRepository->getByHash($hash);
            $this->redisService->cacheWebsites();
            $this->redisService->cacheWebsitesCodes();
        }

        $websiteDto = new WebsiteDto(
            ApiHelper::determineLanguage($request, $website),
            $this->websiteRedisRepository->getAlgorithmsByWebsite($website),
            $this->websiteRedisRepository->getCodeByWebsite($website),
            $website->prepareExcludedDomains($this->websiteRedisRepository->getBlockedDomainsByWebsite($website)),
            $this->websiteRedisRepository->getWhiteListedDomainsByWebsite($website)
        );

        $session = null;
        if (($sid = $request->get('sid'))) { // load more request.
            $session = $this->sessionRepository->get($sid);
            $websiteDto->setExcludedPosts($session->getPosts());
        }

        $data = $this->algorithmManager->getData($website, $websiteDto);
        BannerInformationDto::setInformation($data->get('profiler'));
        if (empty($posts = $data->get('posts'))) return '[]';

        if ($session === null) {
            $session = Session::create($website->getId(), $request->getUserIP(), $request->getUserAgent(), $data->get('algorithm'))
                ->assignPosts($posts);
            $this->sessionRepository->save($session);
        } else {
            $this->sessionRepository->updatePosts($session, $posts);
            $session->assignPosts($posts);
        }

        $this->eventDispatcher->dispatch(new View($session));
        return $this->responseDispatcher->dispatch(
            new ApiResponse(
                $session,
                $this->postRepository->getByIds($posts, ['id', 'title', 'image', 'color']),
                $websiteDto->getLanguage()
            )
        );
    }
}
