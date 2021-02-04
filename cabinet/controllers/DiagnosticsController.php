<?php

namespace cabinet\controllers;

use Yii;
use Exception;
use yii\helpers\Url;
use yii\web\Controller;
use cabinet\models\DiagnosticsSearch;
use core\repositories\interfaces\{WebsiteRepositoryInterface, RssRepositoryInterface};

/**
 * Class DiagnosticsController
 * @package cabinet\controllers
 */
class DiagnosticsController extends Controller
{
    private WebsiteRepositoryInterface $websiteRepository;
    private RssRepositoryInterface $rssRepository;

    public function __construct(
        $id,
        $module,
        WebsiteRepositoryInterface $websiteRepository,
        RssRepositoryInterface $rssRepository,
        $config = []
    )
    {
        parent::__construct($id, $module, $config);

        $this->websiteRepository = $websiteRepository;
        $this->rssRepository = $rssRepository;
    }

    public function actionIndex($id)
    {
        ini_set('memory_limit','2046M');

        try {
            $website = $this->websiteRepository->getOneByCustomer($id, Yii::$app->user->id);
        } catch (Exception $e) {
            return $this->redirect(['website/index']);
        }

        $searchModel = new DiagnosticsSearch($this->rssRepository);
        $dataProvider = $searchModel->search($website, Yii::$app->request->get());
        Url::remember();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'website' => $website,
            'dataProvider' => $dataProvider,
        ]);
    }
}