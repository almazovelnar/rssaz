<?php

namespace core\services\cache;

use Yii;
use core\exceptions\NotFoundException;
use core\entities\Customer\Website\Website;
use yii\redis\Connection as RedisConnection;
use core\repositories\interfaces\{
    WebsiteRepositoryInterface,
    CodeRepositoryInterface,
    BlockedDomainRepositoryInterface,
    WhiteListedDomainRepositoryInterface};

/**
 * Class RedisService
 * @package core\services\cache
 */
class RedisService
{
    public const KEY_CODE = 'website_code';
    public const KEY_WEBSITES = 'websites';
    public const KEY_ALGORITHMS = 'website_algorithms';
    public const KEY_BLOCKED_DOMAINS = 'website_blocked_domains';
    public const KEY_WHITELISTED_DOMAINS = 'website_whitelisted_domains';

    private RedisConnection $redis;
    private CodeRepositoryInterface $codeRepository;
    private WebsiteRepositoryInterface $websiteRepository;
    private BlockedDomainRepositoryInterface $blockedDomainRepository;
    private WhiteListedDomainRepositoryInterface $whiteListedDomainRepository;

    public function __construct(
        CodeRepositoryInterface $codeRepository,
        WebsiteRepositoryInterface $websiteRepository,
        BlockedDomainRepositoryInterface $blockedDomainRepository,
        WhiteListedDomainRepositoryInterface $whiteListedDomainRepository
    )
    {
        $this->redis = Yii::$app->redis;
        $this->codeRepository = $codeRepository;
        $this->websiteRepository = $websiteRepository;
        $this->blockedDomainRepository = $blockedDomainRepository;
        $this->whiteListedDomainRepository = $whiteListedDomainRepository;
    }

    public function cacheWebsites(): void
    {
        $websites = $algorithms = $blockedDomains = $whiteListedDomains = [];

        /** @var Website $website */
        foreach ($this->websiteRepository->all(['status' => Website::STATUS_ACTIVE], ['w.id' => SORT_ASC]) as $website) {
            $websites[$website->getHash()] = $website;
            $algorithms[$website->getId()] = $website->algorithms;
            $blockedDomains[$website->getId()] = ($this->blockedDomainRepository->getByWebsite($website->getId()) ?: []);
            $whiteListedDomains[$website->getId()] = ($this->whiteListedDomainRepository->getByWebsite($website->getId()) ?: []);
        }

        $this->redis->mset(
            self::KEY_WEBSITES, serialize($websites),
            self::KEY_ALGORITHMS, serialize($algorithms),
            self::KEY_BLOCKED_DOMAINS, serialize($blockedDomains),
            self::KEY_WHITELISTED_DOMAINS, serialize($whiteListedDomains),
        );
    }

    public function cacheWebsitesCodes(): void
    {
        $codes = [];
        foreach ($this->codeRepository->all() as $code)
            $codes[$code->getWebsiteId()] = $code;
        $this->redis->set(self::KEY_CODE, serialize($codes));
    }

    public function invalidateCodePageCache(string $hash): void
    {
        $this->redis->expire('cache_' . md5('/code/' . $hash), 200);
        $this->redis->expire('cache_' . md5('/code/' . $hash . '/'), 200);
    }
}