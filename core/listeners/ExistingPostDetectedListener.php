<?php

namespace core\listeners;

use core\services\PostIndexer;
use core\events\ExistingPostDetected;

/**
 * Class ExistingPostDetectedListener
 * @package core\listeners
 */
class ExistingPostDetectedListener
{
    private PostIndexer $postIndexer;

    public function __construct(PostIndexer $postIndexer)
    {
        $this->postIndexer = $postIndexer;
    }

    /**
     * @param ExistingPostDetected $event
     */
    public function handle(ExistingPostDetected $event): void
    {
        // reindexing elastic search nodes.
        foreach ($event->getExistingPosts() as $post)
            $this->postIndexer->index($post);
    }
}