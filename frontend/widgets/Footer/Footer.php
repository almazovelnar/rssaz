<?php

namespace frontend\widgets\Footer;

use Yii;
use yii\base\Widget;
use core\entities\Page\Page;
use yii\caching\CacheInterface;
use core\readModels\PageReadRepository;

/**
 * Class Footer
 * @package frontend\widgets\Footer
 */
class Footer extends Widget
{
    private PageReadRepository $pageReadRepository;
    private CacheInterface $cache;

    public function __construct(
        PageReadRepository $pageReadRepository,
        CacheInterface $cache,
        array $config = []
    )
    {
        parent::__construct($config);

        $this->pageReadRepository = $pageReadRepository;
        $this->cache = $cache;
    }

    public function run()
    {
        return $this->render('index', [
            'pages' => $this->cache->getOrSet(['footer_pages', Yii::$app->language], function () {
                return $this->pageReadRepository->getPages(Page::TYPE_FRONTEND, 5);
            })
        ]);
    }
}