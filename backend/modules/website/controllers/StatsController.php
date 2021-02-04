<?php

namespace backend\modules\website\controllers;

use Yii;
use backend\controllers\BaseAdminController;
use backend\modules\website\models\WebsiteStatsSearch;
use core\repositories\interfaces\WebsiteRepositoryInterface;
use yii\caching\CacheInterface;

/**
 * Class StatsController
 * @package backend\modules\website\controllers
 */
class StatsController extends BaseAdminController
{
    private WebsiteRepositoryInterface $websiteRepository;
    private CacheInterface $cache;

    public function __construct(
        $id,
        $module,
        WebsiteRepositoryInterface $websiteRepository,
        CacheInterface $cache,
        $config = []
    )
    {
        parent::__construct($id, $module, $config);

        $this->websiteRepository = $websiteRepository;
        $this->cache = $cache;
    }

    public function actionIndex()
    {
        $searchModel = new WebsiteStatsSearch($this->websiteRepository);
        $dataProvider = $searchModel->search(Yii::$app->request->get());

        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
            'stats' => $this->cache->get('website_stats'),
        ]);
    }
}