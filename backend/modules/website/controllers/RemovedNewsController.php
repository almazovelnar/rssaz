<?php

namespace backend\modules\website\controllers;

use Yii;
use Exception;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\components\auth\Rbac;
use backend\controllers\BaseAdminController;
use core\useCases\manager\PostRemovalReasonService;
use backend\modules\website\models\RemovedPostSearch;
use core\repositories\interfaces\PostRepositoryInterface;

/**
 * Class RemovedNewsController
 * @package backend\modules\website\controllers
 */
class RemovedNewsController extends BaseAdminController
{
    private PostRepositoryInterface $postRepository;
    private PostRemovalReasonService $postRemovalReasonService;

    public function __construct(
        string $id,
        $module,
        PostRepositoryInterface $postRepository,
        PostRemovalReasonService $postRemovalReasonService,
        array $config = []
    )
    {
        parent::__construct($id, $module, $config);

        $this->postRepository = $postRepository;
        $this->postRemovalReasonService = $postRemovalReasonService;
    }

    public function behaviors(): array
    {
        $params = [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'remove' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => [Rbac::ROLE_ADMIN],
                    ]
                ],
            ]
        ];

        return array_replace(parent::behaviors(), $params);
    }

    public function actionIndex()
    {
        $searchModel = new RemovedPostSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->get());

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @param int $id
     * @return Response
     */
    public function actionDelete(int $id)
    {
        try {
            $this->postRemovalReasonService->delete($id);
        } catch (Exception $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }

        return $this->redirect(['index']);
    }

    /**
     * @param int $id
     * @return Response
     */
    public function actionModerate(int $id)
    {
        try {
            $this->postRemovalReasonService->activate($id);
        } catch (Exception $e) {
            Yii::$app->errorHandler->logException($e);
        }
        return $this->redirect(['index']);
    }
}