<?php

namespace core\useCases\manager;

use Yii;
use Exception;
use RuntimeException;
use core\events\WebsiteUpdated;
use core\dispatchers\EventDispatcher;
use core\repositories\ParseRepository;
use core\exceptions\NotFoundException;
use core\entities\Customer\Website\Website;
use core\repositories\interfaces\{
    RssRepositoryInterface,
    PostRepositoryInterface,
    CodeRepositoryInterface,
    WebsiteRepositoryInterface,
    AlgorithmRepositoryInterface,
    BlockedDomainRepositoryInterface
};
use core\forms\manager\Website\{CreateForm, UpdateForm};

/**
 * Class WebsiteService
 * @package core\useCases\manager
 */
class WebsiteService
{
    private BlockedDomainRepositoryInterface $blockedDomainRepository;
    private AlgorithmRepositoryInterface $algorithmRepository;
    private WebsiteRepositoryInterface $websiteRepository;
    private PostRepositoryInterface $postRepository;
    private CodeRepositoryInterface $codeRepository;
    private RssRepositoryInterface $rssRepository;
    private ParseRepository $parseRepository;
    private EventDispatcher $eventDispatcher;

    public function __construct(
        BlockedDomainRepositoryInterface $blockedDomainRepository,
        AlgorithmRepositoryInterface $algorithmRepository,
        WebsiteRepositoryInterface $websiteRepository,
        CodeRepositoryInterface $codeRepository,
        PostRepositoryInterface $postRepository,
        RssRepositoryInterface $rssRepository,
        ParseRepository $parseRepository,
        EventDispatcher $eventDispatcher
    )
    {
        $this->blockedDomainRepository = $blockedDomainRepository;
        $this->algorithmRepository = $algorithmRepository;
        $this->websiteRepository = $websiteRepository;
        $this->parseRepository = $parseRepository;
        $this->eventDispatcher = $eventDispatcher;
        $this->postRepository = $postRepository;
        $this->codeRepository = $codeRepository;
        $this->rssRepository = $rssRepository;
    }

    /**
     * @param CreateForm $form
     * @throws Exception
     */
    public function create(CreateForm $form)
    {
        try {
            $website = Website::create(
                $form->customerId,
                $form->name,
                $form->trafficLimit,
                $form->address,
                $form->status,
                $form->defaultLanguage,
                $form->rateMin,
                $form->updateFrequency
            );

            $this->websiteRepository->save($website);
            $this->websiteRepository->syncAlgorithms($website, $form->algorithms);
            $this->websiteRepository->syncBlockedDomains($website, $form->getBlockedDomains());
            $this->websiteRepository->syncWhiteListedDomains($website, $form->getWhiteListedDomains());

            foreach ($form->rss as $rssForm) {
                if (!$rssForm->isEmpty()) {
                    $this->rssRepository->save($website->createRss($rssForm->language, $rssForm->rssAddress));
                }
            }

            $this->renewUpdateFrequency($website);
            $this->eventDispatcher->dispatch(new WebsiteUpdated($website));
        } catch (RuntimeException $e) {
            throw $e;
        }
    }

    public function edit(Website $website, UpdateForm $form)
    {
        $website->edit(
            $form->customerId,
            $form->name,
            $form->trafficLimit,
            $form->updateFrequency,
            $form->defaultLanguage,
            $form->address,
            $form->status,
            $form->rateMin
        );
        $this->websiteRepository->update($website->getId(), $website->getDirtyAttributes());
        $this->websiteRepository->syncAlgorithms($website, $form->algorithms);
        $this->websiteRepository->syncBlockedDomains($website, $form->getBlockedDomains());
        $this->websiteRepository->syncWhiteListedDomains($website, $form->getWhiteListedDomains());
        $this->websiteRepository->syncRss($website, $form->rss, true);
        $this->renewUpdateFrequency($website);

        $this->eventDispatcher->dispatch(new WebsiteUpdated($website));
    }

    /**
     * @param $id
     * @throws NotFoundException
     */
    public function activate($id)
    {
        /** @var Website $website */
        $website = $this->websiteRepository->get($id);
        $this->websiteRepository->changeStatus($id, Website::STATUS_ACTIVE);
        $this->renewUpdateFrequency($website);
        $this->eventDispatcher->dispatch(new WebsiteUpdated($website));
    }

    /**
     * @param $id
     * @throws NotFoundException
     */
    public function block($id)
    {
        $website = $this->websiteRepository->get($id);
        $this->websiteRepository->changeStatus($id, Website::STATUS_BLOCKED);
        $this->renewUpdateFrequency($website);
        $this->eventDispatcher->dispatch(new WebsiteUpdated($website));
    }

    /**
     * @param $id
     * @throws NotFoundException
     */
    public function delete($id)
    {
        $website = $this->websiteRepository->get($id);

        $this->rssRepository->removeByWebsite($website);
        $this->codeRepository->removeByWebsite($website);
        $this->postRepository->removeByWebsite($website);
        $this->algorithmRepository->removeByWebsite($website);
        $this->blockedDomainRepository->removeByWebsite($website);
        $this->parseRepository->removeByWebsite($website);

        $this->websiteRepository->remove($website);
    }

    public function renewUpdateFrequency(Website $website): bool
    {
        if (empty($cache = Yii::$app->cache->get('rss_parsing_frequency'))) return false;

        if (!$website->isActive() && array_key_exists($website->id, $cache)) {
            unset($cache[$website->id]);
            return false;
        }

        if (!array_key_exists($website->id, $cache))
            $cache[$website->id] = ['update_frequency' => $website->update_frequency * 60, 'parsed_at' => time()];
        else
            $cache[$website->id]['update_frequency'] = $website->update_frequency * 60;

        return Yii::$app->cache->set('rss_parsing_frequency', $cache);
    }
}