<?php

namespace core\services\code;

use core\services\cache\RedisService;
use core\repositories\WebsiteRedisRepository;
use core\repositories\interfaces\WebsiteRepositoryInterface;
use core\exceptions\{NotFoundCachedException, CodeNotFoundException, NotFoundException};

/**
 * Class CodeService
 * @package core\services\code
 */
class CodeService
{
    private JsParser $parser;
    private WebsiteRedisRepository $websiteRedisRepository;
    private WebsiteRepositoryInterface $websiteRepository;
    private RedisService $redisService;

    public function __construct(
        JsParser $parser,
        WebsiteRedisRepository $websiteRedisRepository,
        WebsiteRepositoryInterface $websiteRepository,
        RedisService $redisService
    )
    {
        $this->parser = $parser;
        $this->websiteRedisRepository = $websiteRedisRepository;
        $this->websiteRepository = $websiteRepository;
        $this->redisService = $redisService;
    }

    /**
     * @param string $hash
     * @return string|null
     * @throws CodeNotFoundException|replacer\Exceptions\ReplacerException|NotFoundException
     */
    public function generate(string $hash): ?string
    {
        try {
            $website = $this->websiteRedisRepository->getWebsiteByHash($hash);
        } catch (NotFoundCachedException $e) {
            $website = $this->websiteRepository->getByHash($hash);
            $this->redisService->cacheWebsites();
            $this->redisService->cacheWebsitesCodes();
        }

        if (!($code = $this->websiteRedisRepository->getCodeByWebsite($website)))
            throw new CodeNotFoundException("Please, generate code for your website from cabinet.");

        $dto = new CodeDto(
            $code->getBlockCount(),
            $code->getBlockWidth(),
            $code->getDirection(),
            $code->getTitleFont(),
            $code->getTitleStyle(),
            $code->getTitleFontSize()
        );
        $dto->setWebsite($website);
        return $this->parser->write($dto);
    }
}
