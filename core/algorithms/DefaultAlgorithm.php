<?php

namespace core\algorithms;

use core\services\api\WebsiteDto;
use core\entities\Customer\Website\Website;
use core\repositories\interfaces\PostRepositoryInterface;

/**
 * Class DefaultAlgorithm
 * @package core\algorithms
 */
class DefaultAlgorithm extends AbstractAlgorithm
{
    private string $identity = 'default';
    private PostRepositoryInterface $postRepository;

    public function __construct(PostRepositoryInterface $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function handle(Website $website): array
    {
        $filters = $this->getFilters();
        $postLimit = $this->calculateLimit();
        $newPosts = $this->postRepository->getNew(5000, $filters);
        $this->fillStack($newPosts, $postLimit['new'], 'new_posts_count');

        // For popular and additional posts.
        $popularPostsStack = $this->postRepository
            ->getPopular(500, $filters)
            ->withinPeriod()
            ->get() ?: $this->postRepository
            ->getPopular(500, $filters)
            ->withinPeriod(2)
            ->get();

        $popularPosts = array_slice($popularPostsStack, 0, 100);

        // Priotizing.
        $prioritizedAdded = 0;
        if (rand(0, 1) === 1) {
            if (!empty($prioritizedPosts = $this->postRepository->getPrioritized(500, $filters))) {
                // Appending first prioritized.
                array_push($this->postStack, (int) $prioritizedPosts[array_rand($prioritizedPosts, 1)]['id']);
                $postLimit['popular'] -= 1;
                $prioritizedAdded++;
            }
        }
        $this->setProfilerInformation('prioritized_posts_count', $prioritizedAdded);

        $this->fillStack($popularPosts, $postLimit['popular'], 'popular_posts_count');
        $this->fillStack($popularPostsStack, $postLimit['additional'], 'additional_posts_count');

        $this->setProfilerInformation('filled_posts_count', 0);
        $postsCount = count($this->postStack);
        if ($postsCount < $this->blockCount) { // stack didn't fill enough.
            $neededPostCount = $this->blockCount - $postsCount;
            $this->fillStack($popularPostsStack, $neededPostCount, 'filled_posts_count');
        }

        return $this->getPostStack();
    }

    public function getIdentity(): string
    {
        return $this->identity;
    }
}