<?php

namespace core\listeners;

use core\events\CodeGenerated;
use core\services\{code\CodeService, cache\RedisService};

/**
 * Class CodeGeneratedListener
 * @package core\listeners
 */
class CodeGeneratedListener
{
    private CodeService $codeService;
    private RedisService $redisService;

    public function __construct(CodeService $codeService, RedisService $redisService)
    {
        $this->codeService = $codeService;
        $this->redisService = $redisService;
    }

    /**
     * @param CodeGenerated $event
     */
    public function handle(CodeGenerated $event): void
    {
        $hash = $event->getCode()->website->getHash();

        $this->redisService->cacheWebsitesCodes();
        $this->redisService->invalidateCodePageCache($hash);
    }
}