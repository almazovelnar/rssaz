<?php

namespace api\controllers;

use Yii;
use Exception;
use yii\web\Controller;
use core\helpers\ApiHelper;
use core\services\code\CodeService;
use core\exceptions\CodeNotFoundException;

/**
 * Class CodeController
 * @package api\controllers
 */
class CodeController extends Controller
{
    private CodeService $codeService;
    private ApiHelper $apiHelper;

    public function __construct(
        string $id,
        $module,
        CodeService $codeService,
        ApiHelper $apiHelper,
        array $config = []
    )
    {
        parent::__construct($id, $module, $config);

        $this->codeService = $codeService;
        $this->apiHelper = $apiHelper;
    }

    public function actionGet(string $hash)
    {
        $this->apiHelper->setResponseContentType(Yii::$app->response);

        try {
            return $this->codeService->generate($hash);
        } catch (CodeNotFoundException $e) {
            return "console.warn('Rss.az: {$e->getMessage()}');";
        } catch (Exception $e) {
            Yii::$app->errorHandler->logException($e);
            return "console.warn('Rss.az: Can not generate code for: {$hash}, please refresh the page.');";
        }
    }

    public function actionGetAmp(string $hash)
    {
        return $this->renderPartial('/amp', [
            'hash' => $hash,
            'url'  => Yii::$app->apiUrlManager->createAbsoluteUrl(['code/get', 'hash' => $hash], true)
        ]);
    }
}
