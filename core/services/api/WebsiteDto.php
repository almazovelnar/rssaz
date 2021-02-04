<?php

namespace core\services\api;

use core\entities\Customer\Website\Code;

/**
 * Class WebsiteDto
 * @package core\services\api
 */
class WebsiteDto
{
    private string $language;
    private array $algorithms;
    private ?Code $code;
    private array $blockedDomains;
    private array $whiteListedDomains;
    private array $excludedPosts;

    public function __construct(
        string $language,
        array $algorithms,
        ?Code $code,
        array $blockedDomains,
        array $whiteListedDomains,
        array $excludedPosts = []
    )
    {
        $this->language = $language;
        $this->algorithms = $algorithms;
        $this->code = $code;
        $this->blockedDomains = $blockedDomains;
        $this->whiteListedDomains = $whiteListedDomains;
        $this->excludedPosts = $excludedPosts;
    }

    public function getLanguage(): string
    {
        return $this->language;
    }

    public function getAlgorithms(): array
    {
        return $this->algorithms;
    }

    public function getCode(): ?Code
    {
        return $this->code;
    }

    public function getBlockedDomains(): array
    {
        return $this->blockedDomains;
    }

    public function getWhiteListedDomains(): array
    {
        return array_column($this->whiteListedDomains, 'whitelisted_id');
    }

    public function setExcludedPosts(array $posts): void
    {
        $this->excludedPosts = $posts;
    }

    public function hasExcludedPosts(): bool
    {
        return !empty($this->excludedPosts);
    }

    public function hasWhiteListedDomains(): bool
    {
        return !empty($this->whiteListedDomains);
    }

    public function getExcludedPosts(): array
    {
        return $this->excludedPosts;
    }
}