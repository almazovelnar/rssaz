<?php

namespace core\algorithms;

use core\entities\Customer\Website\Website;
use core\repositories\interfaces\PostRepositoryInterface;
use core\services\api\WebsiteDto;

/**
 * Class TopHundredPostsAlgorithm
 * @package core\algorithms
 */
class TopHundredPostsAlgorithm extends AbstractAlgorithm
{
    private string $identity = 'top-100';
    private PostRepositoryInterface $postRepository;

    public function __construct(PostRepositoryInterface $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function handle(Website $website): array
    {
        $filters = $this->getFilters();
        $this->fillStack(
            $this->postRepository
                ->getPopular(100, $filters)
                ->withinPeriod()
                ->get(),
            $this->getBlockCount(),
            'top_hundred_posts'
        );

        return $this->getPostStack();
    }

    public function getIdentity(): string
    {
        return $this->identity;
    }
}