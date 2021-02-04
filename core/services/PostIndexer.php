<?php

namespace core\services;

use Yii;
use stdClass;
use Exception;
use Elasticsearch\Client;
use core\entities\Category\Category;
use core\entities\Customer\Website\Post;
use core\repositories\CategoryRepository;
use Elasticsearch\Common\Exceptions\Missing404Exception;

/**
 * Class PostIndexer
 * @package core\services
 */
class PostIndexer
{
    private Client $client;
    private CategoryRepository $categoryRepository;

    public function __construct(Client $client, CategoryRepository $categoryRepository)
    {
        $this->client = $client;
        $this->categoryRepository = $categoryRepository;
    }

    public function clear(): void
    {
        $this->client->deleteByQuery([
            'index' => 'rss',
            'type' => 'news',
            'body' => [
                'query' => [
                    'match_all' => new stdClass(),
                ],
            ],
        ]);
    }

    /**
     * @param Post $post
     */
    public function index(Post $post): void
    {
        /** @var Category $category */
        $category = $this->categoryRepository->get($post->category_id);
        $category->language = $post->lang;
        $website = $post->website;

        try {
            $this->client->index([
                'index' => 'rss',
                'type' => 'news',
                'id' => $post->id,
                'body' => [
                    'id' => $post->id,
                    'website' => [
                        'id' => $website->id,
                        'name' => $website->name,
                        'address' => $website->address,
                    ],
                    'title' => $post->title,
                    'description' => $post->description,
                    'link' => $post->link,
                    'category' => [
                        'id' => (int) $category->id,
                        'url' => (string) $category->slug,
                        'title' => (string) $category->title
                    ],
                    'lang' => $post->lang,
                    'status' => $post->status ?? Post::STATUS_ACTIVE,
                    'image' => $post->image,
                    'views' => $post->views,
                    'created_at' => $post->created_at,
                ]
            ]);
        } catch (Exception $e) {
            Yii::$app->errorHandler->logException($e);
        }
    }

    /**
     * @param int $id
     */
    public function remove(int $id): void
    {
        try {
            $this->client->delete([
                'index' => 'rss',
                'type' => 'news',
                'id' => $id,
            ]);
        } catch (Missing404Exception $e) {}
    }
}