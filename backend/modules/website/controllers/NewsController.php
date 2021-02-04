<?php

namespace backend\modules\website\controllers;

use Yii;
use Exception;
use yii\filters\VerbFilter;
use core\forms\PostRemovalReasonForm;
use core\exceptions\NotFoundException;
use core\entities\Customer\Website\Post;
use backend\controllers\BaseAdminController;
use backend\modules\website\models\PostSearch;
use core\useCases\manager\{PostRemovalReasonService, PostService};
use core\repositories\interfaces\{PostRepositoryInterface, WebsiteRepositoryInterface};

/**
 * Class NewsController
 * @package backend\modules\website\controllers
 */
class NewsController extends BaseAdminController
{
    private PostRemovalReasonService $postRemovalReasonService;
    private WebsiteRepositoryInterface $websiteRepository;
    private PostRepositoryInterface $postRepository;
    private PostService $postService;

    public function __construct(
        string $id,
        $module,
        PostService $postService,
        PostRepositoryInterface $postRepository,
        WebsiteRepositoryInterface $websiteRepository,
        PostRemovalReasonService $postRemovalReasonService,
        array $config = []
    )
    {
        parent::__construct($id, $module, $config);

        $this->postService = $postService;
        $this->postRepository = $postRepository;
        $this->websiteRepository = $websiteRepository;
        $this->postRemovalReasonService = $postRemovalReasonService;
    }

    public function behaviors(): array
    {
        return array_replace(parent::behaviors(), [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'remove' => ['POST'],
                ],
            ],
        ]);
    }

    public function actionIndex()
    {
        $request = Yii::$app->request;
        $searchModel = new PostSearch($this->websiteRepository, Yii::$app->response, Yii::$app->request);
        $dataProvider = $searchModel->search($request->get());

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'request' => $request
        ]);
    }

    public function actionPrioritize(int $id, ?int $page = null)
    {
        try {
            $this->postRepository->changePriority($this->postRepository->get($id, ['status' => Post::STATUS_ACTIVE]), Post::PRIORITY_ADMIN);
        } catch (NotFoundException | Exception $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
        return $this->redirect(['index', 'page' => $page]);
    }

    public function actionDePrioritize(int $id, ?int $page = null)
    {
        try {
            $this->postRepository->changePriority($this->postRepository->get($id, ['status' => Post::STATUS_ACTIVE]), Post::PRIORITY_DEFAULT);
        } catch (NotFoundException | Exception $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
        return $this->redirect(['index', 'page' => $page]);
    }

    public function actionDelete($id)
    {
        try {
            $this->postService->delete($id);
        } catch (NotFoundException | Exception $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }

        return $this->redirect(['index']);
    }

    public function actionModerate(int $id)
    {
        if (Yii::$app->request->isAjax) {
            $form = new PostRemovalReasonForm();
            if ($form->load(Yii::$app->request->post()) && $form->validate()) {
                try {
                    return $this->asJson([
                        'status' => $this->postRemovalReasonService
                            ->create($this->postRepository->get($id, ['status' => Post::STATUS_ACTIVE]), $form)
                        ]);
                } catch (NotFoundException | Exception $e) {
                    Yii::$app->errorHandler->logException($e);
                }
            }
        }

        return $this->asJson(['status' => false]);
    }

    public function actionResetFilters()
    {
        $response = Yii::$app->response->cookies;
        foreach (Yii::$app->request->cookies->toArray() as $key => $value) {
            if (str_contains($key, PostSearch::FILTER_REMEMBER_KEY)) {
                $response->remove($key);
            }
        }

        return $this->redirect('index');
    }
}