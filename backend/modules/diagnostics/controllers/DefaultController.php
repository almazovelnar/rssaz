<?php

namespace backend\modules\diagnostics\controllers;

use Yii;
use RuntimeException;
use core\entities\Parse\Parse;
use yii\data\ArrayDataProvider;
use yii\web\NotFoundHttpException;
use core\repositories\ParseRepository;
use backend\controllers\BaseAdminController;
use backend\modules\diagnostics\models\DiagnosticsSearch;
use core\repositories\interfaces\{WebsiteRepositoryInterface, RssRepositoryInterface};

/**
 * Class DefaultController
 * @package backend\modules\diagnostics\controllers
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
        $searchModel = new DiagnosticsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->get());

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView(int $id)
    {
        $parse = $this->findParse($id);
        return $this->render('view', [
            'parse' => $parse,
            'rss' => $this->rssRepository->get($parse->rss_id),
            'website' => $this->websiteRepository->get($parse->website_id),
            'dataProvider' => new ArrayDataProvider([
                'allModels' => $parse->parseErrors,
                'pagination' => ['pageSize' => 20],
            ])
        ]);
    }

    public function actionDelete(int $id)
    {
        try {
            $this->parseRepository->remove($this->findParse($id));
        } catch (RuntimeException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }

        return $this->redirect(['index']);
    }

    private function findParse($id)
    {
        if (($model = Parse::findOne($id)) !== null)
            return $model;

        throw new NotFoundHttpException("Can't find parse !");
    }
}