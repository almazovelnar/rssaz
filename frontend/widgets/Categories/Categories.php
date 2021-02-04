<?php

namespace frontend\widgets\Categories;

use Yii;
use yii\base\Widget;
use yii\caching\CacheInterface;
use core\entities\Category\Category;
use core\readModels\CategoryReadRepository;
use core\repositories\interfaces\PostRepositoryInterface;

/**
 * Class Categories
 * @package frontend\widgets\Categories
 */
class Categories extends Widget
{
    private CacheInterface $cache;
    private PostRepositoryInterface $postRepository;
    private CategoryReadRepository $categoryReadRepository;

    public function __construct(
        CategoryReadRepository $categoryReadRepository,
        PostRepositoryInterface $postRepository,
        CacheInterface $cache,
        array $config = []
    )
    {
        parent::__construct($config);

        $this->categoryReadRepository = $categoryReadRepository;
        $this->postRepository = $postRepository;
        $this->cache = $cache;
    }

    public function run()
    {
        $categories = $this->cache->getOrSet(['frontend_categories', Yii::$app->language], fn () => $this->categoryReadRepository->getAll());
        shuffle($categories);

        $posts = [];
        /** @var Category $category */
        foreach ($categories as $index => $category) {
            if (count($posts) >= 24) break;

            if (count($data = $this->cache->getOrSet(
                    ['frontend_category_posts_' . $category->id , Yii::$app->language], fn () => $this->postRepository->getByCategoryInDays($category->id, 3, 7), 7200)
                ) < 3) {
                unset($categories[$index]);
                continue;
            }

            $posts[$category->id] = $data;
        }

        $columns = [];
        for ($i = 0; $i < 4; $i++)
            $columns[] = array_splice($categories, 0, 2);

        return $this->render('index', compact('columns', 'posts'));
    }
}
