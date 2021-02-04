<?php

namespace core\useCases\cabinet;

use Yii;
use RuntimeException;
use core\entities\Customer\Website\Post;
use Tightenco\Collect\Support\Collection;
use core\repositories\interfaces\PostRepositoryInterface;

/**
 * Class PostService
 * @package core\useCases\cabinet
 */
class PostService
{
    private PostRepositoryInterface $postRepository;

    public function __construct(PostRepositoryInterface $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function prioritize(Post $post): void
    {
        $customer = Yii::$app->user->identity;
        $cacheKey = 'customer_prioritizing';

        /** @var Collection $stack */
        $stack = Yii::$app->cache->getOrSet($cacheKey, fn () => collect());

        if (!$stack->has($customer->getId()))
            $stack->put($customer->getId(), [$post->website_id => []]);

        $information = $stack->get($customer->getId());

        if (!in_array($post->website_id, array_keys($information)))
            $information[$post->website_id] = [];

        $posts = $information[$post->website_id];

        if (count($posts) >= 10)
            throw new RuntimeException("Post prioritizing limit reached");

        if ($post->prioritized()) // post can be prioritized by admin also.
            throw new RuntimeException("Post already prioritized");

        if (($key = array_search($post->id, $posts)) !== false) unset($posts[$key]);

        $this->postRepository->update($post->id, ['priority' => Post::PRIORITY_CUSTOMER]);

        $information[$post->website_id][] = $post->id;
        $stack->put($customer->getId(), $information);
        Yii::$app->cache->set($cacheKey, $stack);
    }
}
