<?php

namespace backend\modules\antifraud\controllers;

use Yii;
use backend\controllers\BaseAdminController;
use backend\modules\antifraud\models\TopIPSearch;

/**
 * Class IpController
 * @package backend\modules\antifraud\controllers
 */
class IpController extends BaseAdminController
{
    public function actionIndex()
    {
        $searchModel = new TopIPSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->get());

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}