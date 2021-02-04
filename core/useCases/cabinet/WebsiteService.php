<?php

namespace core\useCases\cabinet;

use Yii;
use Exception;
use RuntimeException;
use core\events\WebsiteUpdated;
use core\dispatchers\EventDispatcher;
use core\exceptions\NotFoundException;
use core\forms\cabinet\Website\{CreateForm, UpdateForm};
use core\entities\Customer\Website\{Website, Algorithm};
use core\repositories\interfaces\{
    BlockedDomainRepositoryInterface,
    AlgorithmRepositoryInterface,
    WebsiteRepositoryInterface,
    CodeRepositoryInterface,
    PostRepositoryInterface,
    RssRepositoryInterface
};

/**
 * Class WebsiteService
 * @package core\useCases\cabinet
 */
class WebsiteService
{
    private BlockedDomainRepositoryInterface $blockedDomainRepository;
    private AlgorithmRepositoryInterface $algorithmRepository;
    private WebsiteRepositoryInterface $websiteRepository;
    private CodeRepositoryInterface $codeRepository;
    private RssRepositoryInterface $rssRepository;
    private PostRepositoryInterface $postRepository;
    private EventDispatcher $eventDispatcher;

    public function __construct(
        BlockedDomainRepositoryInterface $blockedDomainRepository,
        AlgorithmRepositoryInterface $algorithmRepository,
        WebsiteRepositoryInterface $websiteRepository,
        CodeRepositoryInterface $codeRepository,
        PostRepositoryInterface $postRepository,
        RssRepositoryInterface $rssRepository,
        EventDispatcher $eventDispatcher
    )
    {
        $this->blockedDomainRepository = $blockedDomainRepository;
        $this->algorithmRepository = $algorithmRepository;
        $this->websiteRepository = $websiteRepository;
        $this->eventDispatcher = $eventDispatcher;
        $this->postRepository = $postRepository;
        $this->codeRepository = $codeRepository;
        $this->rssRepository = $rssRepository;
    }

    /**
     * @param CreateForm $form
     * @return Website
     * @throws Exception
     */
    public function register(CreateForm $form): Website
    {
        try {
            $record = Website::create(
                Yii::$app->user->id,
                $form->name,
                $form->trafficLimit,
                $form->address,
                Website::STATUS_WAITING,
                $form->language,
                1.0
            );
            $website = $this->websiteRepository->save($record);
            $this->websiteRepository->syncAlgorithms($website, array_wrap(Algorithm::DEFAULT_ALGORITHM));
            $this->rssRepository->save($website->createRss($form->language, $form->rssAddress));
            return $website;
        } catch (RuntimeException $e) {
            throw $e;
        }
    }

    /**
     * @param Website $website
     * @param UpdateForm $form
     */
    public function edit(Website $website, UpdateForm $form)
    {
        $this->websiteRepository->update($website->getId(), [
            'name' => $form->name,
            'traffic_limit' => $form->trafficLimit,
            'update_frequency' => $form->updateFrequency,
            'default_lang' => $form->defaultLanguage,
        ]);

        $this->websiteRepository->syncBlockedDomains($website, $form->getBlockedDomains());
        $this->websiteRepository->syncWhiteListedDomains($website, $form->getWhiteListedDomains());
        $this->websiteRepository->syncRss($website, $form->rss);

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

        $this->websiteRepository->remove($website);
    }

    public function renewUpdateFrequency(Website $website)
    {
        $cache = Yii::$app->cache->get('rss_parsing_frequency');

        if (!empty($cache)) {
            if (!array_key_exists($website->id, $cache))
                $cache[$website->id] = ['update_frequency' => $website->update_frequency * 60, 'parsed_at' => time()];
            else if(($website->update_frequency * 60) != $cache[$website->id]['update_frequency'])
                $cache[$website->id]['update_frequency'] = $website->update_frequency * 60;

            Yii::$app->cache->set('rss_parsing_frequency', $cache);
        }
    }
}