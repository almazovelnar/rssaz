<?php

namespace core\repositories;

use Yii;
use core\services\cache\RedisService;
use core\entities\Customer\Website\Code;
use core\exceptions\NotFoundCachedException;
use core\entities\Customer\Website\Website;
use yii\redis\Connection as RedisConnection;

/**
 * Class WebsiteRedisRepository
 * @package core\repositories
 */
class WebsiteRedisRepository
{
    private RedisConnection $redis;

    public function __construct()
    {
        $this->redis = Yii::$app->redis;
    }

    /**
     * @param string $hash
     * @return Website
     * @throws NotFoundCachedException
     */
    public function getWebsiteByHash(string $hash): Website
    {
        $websites = $this->getWebsites();
        if (!array_key_exists($hash, $websites))
            throw new NotFoundCachedException("Can't find website in redis cache with hash: {$hash}");
        return $websites[$hash];
    }

    /**
     * @param Website $website
     * @return Code|null
     */
    public function getCodeByWebsite(Website $website): ?Code
    {
        return $this->getCodes()[$website->getId()] ?? null;
    }

    public function getAlgorithmsByWebsite(Website $website): array
    {
        return $this->getAlgorithms()[$website->getId()] ?? [];
    }

    public function getBlockedDomainsByWebsite(Website $website): array
    {
        return $this->getBlockedDomains()[$website->getId()] ?? [];
    }

    public function getWhiteListedDomainsByWebsite(Website $website): array
    {
        return $this->getWhiteListedDomains()[$website->getId()] ?? [];
    }

    private function getWebsites(): array
    {
        return unserialize($this->redis->get(RedisService::KEY_WEBSITES)) ?: [];
    }

    private function getAlgorithms(): array
    {
        return unserialize($this->redis->get(RedisService::KEY_ALGORITHMS)) ?: [];
    }

    private function getCodes(): array
    {
        return unserialize($this->redis->get(RedisService::KEY_CODE)) ?: [];
    }

    private function getBlockedDomains(): array
    {
        return unserialize($this->redis->get(RedisService::KEY_BLOCKED_DOMAINS)) ?: [];
    }

    private function getWhiteListedDomains(): array
    {
        return unserialize($this->redis->get(RedisService::KEY_WHITELISTED_DOMAINS)) ?: [];
    }
}