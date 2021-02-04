<?php

namespace core\listeners;

use core\services\PostIndexer;
use core\events\ParseFinished;
use core\entities\Customer\Website\Post;
use Tightenco\Collect\Support\Collection;
use core\repositories\interfaces\PostRepositoryInterface;

/**
 * Class ParseFinishedSearchListener
 * @package core\listeners
 */
class ParseFinishedSearchListener
{
    private PostIndexer $postIndexer;
    private PostRepositoryInterface $postRepository;

    public function __construct(
        PostIndexer $postIndexer,
        PostRepositoryInterface $postRepository
    )
    {
        $this->postIndexer = $postIndexer;
        $this->postRepository = $postRepository;
    }

    /**
     * @param ParseFinished $event
     */
    public function handle(ParseFinished $event): void
    {
        $parserDto = $event->getParserDto();

        $this->indexParsedPosts($parserDto->getParsedPosts());
        $this->processIndexing($parserDto->getExistingPosts());
    }

    private function indexParsedPosts(Collection $posts): void
    {
        if ($posts->isEmpty()) return;

        $this->processIndexing($this->postRepository->query()
            ->with(['website'])
            ->filter(['ids' => $posts->pluck('id')->toArray(), 'status' => Post::STATUS_ACTIVE])
            ->get());
    }

    private function processIndexing(iterable $posts): void
    {
        foreach ($posts as $post)
            $this->postIndexer->index($post);
    }
}