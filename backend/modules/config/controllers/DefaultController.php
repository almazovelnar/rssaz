<?php

namespace backend\modules\config\controllers;

use Yii;
use core\entities\Config;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use backend\controllers\BaseAdminController;
use backend\modules\config\models\ConfigSearch;

/**
 * Class DefaultController
 * @package backend\modules\config\controllers
 */
class DefaultController extends BaseAdminController
{
    public function behaviors(): array
    {
        $params = [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];

        return array_replace(parent::behaviors(), $params);
    }

    public function actionIndex()
    {
        $searchModel = new ConfigSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->cache->delete('configs');
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Finds the Config model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Config the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Config::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
