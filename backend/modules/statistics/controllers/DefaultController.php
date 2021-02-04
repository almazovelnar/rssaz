<?php

namespace backend\modules\statistics\controllers;

use Yii;
use Exception;
use RuntimeException;
use core\helpers\DiagnosticsHelper;
use core\repositories\ParseRepository;
use backend\controllers\BaseAdminController;
use backend\modules\statistics\models\{StatisticsSearch, StatisticsViewSearch};
use core\repositories\interfaces\{WebsiteRepositoryInterface, RssRepositoryInterface};

/**
 * Class DefaultController
 * @package backend\modules\statistics\controllers
 */
class DefaultController extends BaseAdminController
{
    private ParseRepository $parseRepository;
    private RssRepositoryInterface $rssRepository;
    private WebsiteRepositoryInterface $websiteRepository;

    public function __construct(
        string $id,
        $module,
        ParseRepository $parseRepository,
        RssRepositoryInterface $rssRepository,
        WebsiteRepositoryInterface $websiteRepository,
        array $config = []
    )
    {
        parent::__construct($id, $module, $config);

        $this->websiteRepository = $websiteRepository;
        $this->parseRepository = $parseRepository;
        $this->rssRepository = $rssRepository;
    }

    public function actionIndex()
    {
        $searchModel = new StatisticsSearch($this->websiteRepository, $this->rssRepository);
        $dataProvider = $searchModel->search(Yii::$app->request->get());

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView()
    {
        try {
            $request = Yii::$app->request;

            $searchModel = new StatisticsViewSearch();
            $dataProvider = $searchModel->search($request->get());

            $website = $this->websiteRepository->get($request->get('website'));
            $status = DiagnosticsHelper::statusName($request->get('status'));
        } catch (RuntimeException | Exception $e) {
            Yii::$app->errorHandler->logException($e);
            return $this->redirect(['index']);
        }

        return $this->render('view', [
            'searchModel' => $searchModel,
            'website' => $website,
            'status' => $status,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionDelete(int $id, int $website, int $status)
    {
        try {
            $this->parseRepository->remove($this->parseRepository->get($id));
        } catch (RuntimeException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }

        return $this->redirect(['view', 'website' => $website, 'status' => $status]);
    }
}