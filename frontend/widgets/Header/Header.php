<?php

namespace frontend\widgets\Header;

use Yii;
use yii\base\Widget;
use yii\caching\CacheInterface;
use core\readModels\CategoryReadRepository;

/**
 * Class Header
 * @package frontend\widgets\Header
 */
class Header extends Widget
{
    private CategoryReadRepository $categories;
    private CacheInterface $cache;

    public function __construct(CategoryReadRepository $categories, CacheInterface $cache, array $config = [])
    {
        parent::__construct($config);

        $this->categories = $categories;
        $this->cache = $cache;
    }

    public function run()
    {
        return $this->render('index', [
            'categories' => $this->cache->getOrSet(['frontend_menu_categories', Yii::$app->language], fn() => $this->categories->getMenu()),
        ]);
    }
}