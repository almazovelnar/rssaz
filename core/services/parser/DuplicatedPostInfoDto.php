<?php

namespace core\services\parser;

use Tightenco\Collect\Support\Collection;

/**
 * Class DuplicatedPostInfoDto
 * @package core\services\parser
 */
class DuplicatedPostInfoDto
{
    private int $originalId;
    private int $duplicateId;
    private string $reason;
    private float $similarity;

    public function __construct(int $originalId, string $reason, float $similarity)
    {
        $this->originalId = $originalId;
        $this->reason = $reason;
        $this->similarity = $similarity;
    }

    public function setDuplicateId(int $duplicateId): self
    {
        $this->duplicateId = $duplicateId;
        return $this;
    }

    public function getOriginalId(): int
    {
        return $this->originalId;
    }

    public function getDuplicateId(): int
    {
        return $this->duplicateId;
    }

    public function getReason(): string
    {
        return $this->reason;
    }
    
    public function getSimilarity(): float
    {
        return $this->similarity;
    }
}
