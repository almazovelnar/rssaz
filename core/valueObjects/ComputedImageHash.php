<?php

namespace core\valueObjects;

/**
 * Class ComputedImageHash
 * @package core\valueObjects
 */
class ComputedImageHash
{
    private string $computedHash;

    public function __construct(string $computedHash)
    {
        $this->computedHash = $computedHash;
    }

    public function getFirstChunk(): string
    {
        return mb_substr($this->computedHash, 0, 4);
    }

    public function getLastChunk(): string
    {
        return mb_substr($this->computedHash, strlen($this->computedHash) - 5);
    }
}