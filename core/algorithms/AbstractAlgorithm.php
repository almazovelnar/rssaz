<?php

namespace core\algorithms;

use core\entities\Customer\Website\Website;
use core\services\api\WebsiteDto;

/**
 * Class AbstractAlgorithm
 * @package core\algorithms
 */
abstract class AbstractAlgorithm
{
    protected array $postStack = [];
    protected int $blockCount = 5;
    protected array $profilerInformation = [];
    protected WebsiteDto $websiteDto;

    abstract public function handle(Website $website): array;
    abstract public function getIdentity(): string;

    protected function fillStack(array $posts, int $limit, string $keyForProfiler): void
    {
        $added = 0;
        $posts = array_diff(array_column($posts, 'id'), $this->postStack); // getting unique values for stack.
        $count = count($posts);

        if ($count > 0) {
            $limit = ($count < $limit) ? $count : $limit;
            $randPosts = array_wrap(array_rand(array_flip($posts), $limit));

            $this->postStack = array_merge($this->postStack, $randPosts);
            $added = count($randPosts);
        }

        $this->setProfilerInformation($keyForProfiler, $added);
    }

    protected function calculateLimit(): array
    {
        $specialBlockNumbers = [7, 10, 13, 16];
        $limit = (int) ceil($this->blockCount / 3);
        $remainingPosts = (int) ($this->blockCount / 3);
        return [
            'new' => $limit,
            'popular' => (in_array($this->blockCount, $specialBlockNumbers)) ? $remainingPosts : $limit,
            'additional' => $remainingPosts,
        ];
    }

    public function setProfilerInformation(string $key, $value): void
    {
        $this->profilerInformation[$key] = $value;
    }

    public function getProfilerInformation(): array
    {
        return $this->profilerInformation;
    }

    public function setBlockCount(int $blockCount): self
    {
        $this->blockCount = $blockCount;
        return $this;
    }

    public function getBlockCount(): int
    {
        return $this->blockCount;
    }

    public function getPostStack(): array
    {
        return $this->postStack;
    }

    public function getWebsiteDto(): WebsiteDto
    {
        return $this->websiteDto;
    }

    public function setWebsiteDto(WebsiteDto $websiteDto): self
    {
        $this->websiteDto = $websiteDto;
        return $this;
    }

    public function getFilters(): array
    {
        $filters = ['language' => $this->websiteDto->getLanguage()];
        if ($this->websiteDto->hasExcludedPosts())
            $filters['excludedIds'] = $this->websiteDto->getExcludedPosts();

        if (!$this->websiteDto->hasWhiteListedDomains()) {
            $filters['excludedDomains'] = $this->websiteDto->getBlockedDomains();
            $filters['rate'] = true;
        } else {
            $filters['whiteListedDomains'] = $this->websiteDto->getWhiteListedDomains();
        }

        return $filters;
    }
}
