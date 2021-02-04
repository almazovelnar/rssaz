<?php

namespace core\events;

use Tightenco\Collect\Support\Collection;

/**
 * Class ExistingPostDetected
 * @package core\services\parser\events
 */
class ExistingPostDetected
{
    private Collection $existingPosts;

    public function __construct(Collection $existingPosts)
    {
        $this->existingPosts = $existingPosts;
    }

    public function getExistingPosts(): Collection
    {
        return $this->existingPosts;
    }
}
