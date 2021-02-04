<?php

namespace core\services\parser;

use Tightenco\Collect\Support\Collection;
use core\exceptions\RssParseItemException;
use core\entities\Customer\Website\{Rss, Post};

/**
 * Class ParserDto
 * @package core\services\parser
 */
class ParserDto
{
    private Rss $parsedRssEntity;
    private Collection $parsedPosts;
    private Collection $rssParsedPosts;
    private Collection $existingPosts;
    private Collection $duplicatesInfos;
    private array $postsImages;
    private ?string $parsedXmlContent;
    private ?float $elapsedTime;
    private ?array $lastPosts;
    private array $parseItemErrors;

    public function __construct()
    {
        $this->parsedPosts = collect();
        $this->existingPosts = collect();
        $this->duplicatesInfos = collect();
    }

    public function prepareStacks(): self
    {
        $this->rssParsedPosts = collect();
        $this->postsImages = [];
        $this->parseItemErrors = []; // refactor this to collection also.
        return $this;
    }

    public function appendRssParsed(Post $post): void
    {
        $this->rssParsedPosts->push($post);
    }

    public function appendParsed(Post $post): void
    {
        $this->parsedPosts->push($post);
    }

    public function appendExisting(Post $post): void
    {
        $this->existingPosts->push($post);
    }

    public function getRssParsedPosts(): Collection
    {
        return $this->rssParsedPosts;
    }

    public function getParsedPosts(): Collection
    {
        return $this->parsedPosts;
    }

    public function getExistingPosts(): Collection
    {
        return $this->existingPosts;
    }

    public function setLastPosts(array $posts): self
    {
        $this->lastPosts = $posts;
        return $this;
    }

    public function getLastPosts(): array
    {
        return $this->lastPosts;
    }

    public function appendDuplicatesInfo(DuplicatedPostInfoDto $duplicatedPostInfoDto): void
    {
        $this->duplicatesInfos->push($duplicatedPostInfoDto);
    }

    public function getDuplicatesInfos(): Collection
    {
        return $this->duplicatesInfos;
    }

    public function appendParseItemError(RssParseItemException $e): void
    {
        array_push($this->parseItemErrors, $e);
    }

    public function getParseItemErrors(): array
    {
        return $this->parseItemErrors;
    }

    public function hasParseItemErrors(): bool
    {
        return !empty($this->parseItemErrors);
    }

    public function setParsedRssEntity(Rss $rss): self
    {
        $this->parsedRssEntity = $rss;

        return $this;
    }

    public function getParsedRssEntity(): Rss
    {
        return $this->parsedRssEntity;
    }

    public function setElapsedTime(?float $elapsedTime): self
    {
        $this->elapsedTime = $elapsedTime;

        return $this;
    }

    public function getElapsedTime(): ?float
    {
        return $this->elapsedTime;
    }

    public function setParsedXmlContent(?string $parsedXmlContent): self
    {
        $this->parsedXmlContent = $parsedXmlContent;

        return $this;
    }

    public function getParsedXmlContent(): ?string
    {
        return $this->parsedXmlContent;
    }

    public function setPostsImages(array $postsImages = []): void
    {
        $this->postsImages = $postsImages;
    }

    public function getPostsImages(): ?array
    {
        return $this->postsImages;
    }
}
