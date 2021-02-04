<?php

namespace backend\modules\page\controllers;

use Yii;
use yii\filters\VerbFilter;
use core\entities\Page\Page;
use core\forms\manager\Page\Form;
use yii\web\NotFoundHttpException;
use core\useCases\manager\PageService;
use backend\modules\page\models\PageSearch;
use backend\controllers\BaseAdminController;

/**
 * Class DefaultController
 * @package backend\modules\page\controllers
 */
class DefaultController extends BaseAdminController
{
    private PageService $pageService;

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

    public function __construct(
        string $id,
        $module,
        PageService $pageService,
        array $config = [])
    {
        parent::__construct($id, $module, $config);

        $this->pageService = $pageService;
    }

    public function actionIndex()
    {
        $searchModel = new PageSearch();
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
                $this->pageService->create($form);
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
        $page = $this->findModel($id);
        $form = new Form($page);

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->pageService->update($page, $form);
                Yii::$app->session->setFlash('success', 'Dəyişikliklər yadda saxlanıldı');

                return $this->redirect(['update', 'id' => $page->id]);
            } catch (\DomainException $e) {
                Yii::$app->session->setFlash('error', $e->getMessage());
                Yii::$app->errorHandler->logException($e);
            }
        }

        return $this->render('update', [
            'model' => $form,
            'page'  => $page,
        ]);
    }

    public function actionDelete($id)
    {
        try {
            $this->pageService->delete($id);
            Yii::$app->session->setFlash('success', 'Səhifə uğurla silindi');
        } catch (\DomainException $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
            Yii::$app->errorHandler->logException($e);
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Page model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Page the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Page::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
