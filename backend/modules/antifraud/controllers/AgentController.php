<?php

namespace backend\modules\antifraud\controllers;

use Yii;
use backend\controllers\BaseAdminController;
use backend\modules\antifraud\models\TopAgentSearch;

/**
 * Class AgentController
 * @package backend\modules\antifraud\controllers
 */
class AgentController extends BaseAdminController
{
    public function actionIndex()
    {
        $searchModel = new TopAgentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->get());

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}