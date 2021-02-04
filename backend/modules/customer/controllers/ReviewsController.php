<?php

namespace backend\modules\customer\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use core\entities\Customer\Review\Review;
use core\forms\manager\CustomerReview\Form;
use backend\controllers\BaseAdminController;
use core\useCases\manager\CustomerReviewService;
use backend\modules\customer\models\ReviewSearch;

/**
 * Class ReviewsController
 * @package backend\modules\customer\controllers
 */
class ReviewsController extends BaseAdminController
{
    private CustomerReviewService $customerReviewService;

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
        CustomerReviewService $customerReviewService,
        array $config = [])
    {
        parent::__construct($id, $module, $config);

        $this->customerReviewService = $customerReviewService;
    }

    public function actionIndex()
    {
        $searchModel = new ReviewSearch();
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
                $this->customerReviewService->create($form);
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
        $review = $this->findModel($id);
        $form = new Form($review);

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->customerReviewService->update($review, $form);
                Yii::$app->session->setFlash('success', 'Dəyişikliklər yadda saxlanıldı');

                return $this->redirect(['update', 'id' => $review->id]);
            } catch (\DomainException $e) {
                Yii::$app->session->setFlash('error', $e->getMessage());
                Yii::$app->errorHandler->logException($e);
            }
        }

        return $this->render('update', [
            'model' => $form,
            'review'  => $review,
        ]);
    }

    public function actionDelete($id)
    {
        try {
            $this->customerReviewService->delete($id);
            Yii::$app->session->setFlash('success', 'Rəy uğurla silindi');
        } catch (\DomainException $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
            Yii::$app->errorHandler->logException($e);
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Review model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     * @return Review the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Review::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
