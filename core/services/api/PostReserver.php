<?php

namespace core\services\api;

use InvalidArgumentException;
use Tightenco\Collect\Support\Collection;
use core\repositories\interfaces\PostRepositoryInterface;

/**
 * Class PostReserver
 * @package core\services\api
 */
class PostReserver
{
    private PostRepositoryInterface $postRepository;

    public function __construct(PostRepositoryInterface $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function getPosts(array $posts, int $blockCount, WebsiteDto $websiteDto): Collection
    {
        return $this->fillStack(
            $this->postRepository->getReservedPosts([
                'language' => $websiteDto->getLanguage(),
                'excludedIds' => $websiteDto->getExcludedPosts(),
                'excludedDomains' => $websiteDto->getBlockedDomains(),
            ]),
            $posts,
            $blockCount
        );
    }

    private function fillStack(array $reservedPosts, array $posts, int $blockCount): Collection
    {
        try {
            $collection = collect($reservedPosts)
                ->map(fn ($post) => intval($post['id']))
                ->random($blockCount - count($posts));
            return collect(['reservedCount' => $collection->count(), 'posts' => array_merge($posts, $collection->toArray())]);
        } catch (InvalidArgumentException $e) { // Collection has fewer items than requested.
            return collect(['reservedCount' => 0, 'posts' => $posts]);
        }
    }
}
