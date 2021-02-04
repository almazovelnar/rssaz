<?php

namespace backend\modules\category\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use core\entities\Category\Category;
use core\forms\manager\Category\Form;
use core\useCases\manager\CategoryService;
use backend\controllers\BaseAdminController;
use backend\modules\category\models\CategorySearch;

/**
 * Class DefaultController
 * @package backend\modules\category\controllers
 */
class DefaultController extends BaseAdminController
{
    private CategoryService $categoryService;

    public function __construct(
        string $id,
        $module,
        CategoryService $categoryService,
        array $config = []
    )
    {
        $this->categoryService = $categoryService;
        parent::__construct($id, $module, $config);
    }

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
        $searchModel = new CategorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate()
    {
        $form = new Form();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->categoryService->create($form);
                return $this->redirect(['index']);
            } catch (\DomainException $e) {
                Yii::$app->session->setFlash('error', $e->getMessage());
                Yii::$app->errorHandler->logException($e);
            }
        }

        return $this->render('create', [
            'model' => $form,
        ]);
    }

    public function actionUpdate($id)
    {
        $category = $this->findModel($id);
        $form = new Form($category);

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->categoryService->edit($id, $form);
                return $this->redirect(['index']);
            } catch (\DomainException $e) {
                Yii::$app->session->setFlash('error', $e->getMessage());
                Yii::$app->errorHandler->logException($e);
            }
        }

        return $this->render('update', [
            'model' => $form,
            'category' => $category
        ]);
    }

    /**
     * @param $id
     * @return \yii\web\Response
     */
    public function actionMoveUp($id)
    {
        try {
            $this->categoryService->moveUp($id);
        } catch (\DomainException $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
            Yii::$app->errorHandler->logException($e);
        }

        return $this->redirect(['index']);
    }

    /**
     * @param $id
     * @return \yii\web\Response
     */
    public function actionMoveDown($id)
    {
        try {
            $this->categoryService->moveDown($id);
        } catch (\DomainException $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
            Yii::$app->errorHandler->logException($e);
        }

        return $this->redirect(['index']);
    }

    public function actionDelete($id)
    {
        try {
            $this->categoryService->delete($id);
        } catch (\DomainException $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
            Yii::$app->errorHandler->logException($e);
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Category model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Category the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Category::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
