<?php

namespace backend\modules\antifraud\controllers;

use Yii;
use backend\controllers\BaseAdminController;
use backend\modules\antifraud\models\AntiFraudSearch;

/**
 * Class DefaultController
 * @package backend\modules\antifraud\controllers
 */
class DefaultController extends BaseAdminController
{
    public function actionIndex()
    {
        $searchModel = new AntiFraudSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->get());

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}