<?php

namespace api\controllers;

use Yii;
use Exception;
use yii\web\Controller;
use core\helpers\ApiHelper;
use core\services\api\ApiService;

/**
 * Class DataController
 * @package api\controllers
 */
class DataController extends Controller
{
    private ApiService $apiService;
    private ApiHelper $apiHelper;

    public function __construct(
        string $id,
        $module,
        ApiService $apiService,
        ApiHelper $apiHelper,
        array $config = []
    )
    {
        parent::__construct($id, $module, $config);

        $this->apiService = $apiService;
        $this->apiHelper = $apiHelper;
    }

    public function actionGet(string $hash)
    {
        $this->apiHelper->setResponseContentType(Yii::$app->response);
        try {
            return 'window.rssData = ' . $this->apiService->getBlocks($hash, Yii::$app->request) . ';';
        } catch (Exception $e) {
            Yii::$app->errorHandler->logException($e);
            return 'window.rssData = [];';
        }
    }
}