<?php

namespace core\listeners;

use Yii;
use Exception;
use GuzzleHttp\Client;
use core\events\WebsiteUpdated;
use core\services\cache\RedisService;
use Symfony\Component\DomCrawler\Crawler;
use core\components\Storage\directories\WebsiteDirectory;

/**
 * Class WebsiteUpdatedListener
 * @package core\listeners
 */
class WebsiteUpdatedListener
{
    private Client $client;
    private WebsiteDirectory $websiteDirectory;
    private RedisService $redisService;

    public function __construct(Client $client, WebsiteDirectory $websiteDirectory, RedisService $redisService)
    {
        $this->client = $client;
        $this->redisService = $redisService;
        $this->websiteDirectory = $websiteDirectory;
    }

    public function handle(WebsiteUpdated $event)
    {
        $website = $event->getWebsite();
        try {
            $this->redisService->cacheWebsites();

            $response = $this->client->get($website->address);
            $content = new Crawler($response->getBody()->getContents());
            $icon = $content->filter("link[rel~='shortcut\ icon']");

            if ($icon->count() == 0) $icon = $content->filter("link[rel~='icon']");

            if ($icon->count() == 0) return;
            $this->websiteDirectory->saveIcon($website, $icon->attr('href'));
        } catch (Exception $e) {
            Yii::$app->errorHandler->logException($e);
        }
    }
}
