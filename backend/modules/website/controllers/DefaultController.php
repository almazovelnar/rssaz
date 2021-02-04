<?php

namespace backend\modules\website\controllers;

use Yii;
use Exception;
use DomainException;
use RuntimeException;
use yii\filters\VerbFilter;
use core\services\api\PostReserver;
use core\exceptions\NotFoundException;
use core\useCases\manager\WebsiteService;
use core\repositories\CustomerRepository;
use backend\controllers\BaseAdminController;
use backend\modules\website\models\WebsiteSearch;
use core\forms\manager\Website\{CreateForm, UpdateForm};
use core\repositories\interfaces\WebsiteRepositoryInterface;

/**
 * Class DefaultController
 * @package backend\modules\website\controllers
 */
class DefaultController extends BaseAdminController
{
    private PostReserver $postReserver;
    private WebsiteService $websiteService;
    private CustomerRepository $customerRepository;
    private WebsiteRepositoryInterface $websiteRepository;

    public function __construct(
        string $id,
        $module,
        WebsiteService $websiteService,
        CustomerRepository $customerRepository,
        PostReserver $postReserver,
        WebsiteRepositoryInterface $websiteRepository,
        array $config = []
    )
    {
        parent::__construct($id, $module, $config);

        $this->websiteService = $websiteService;
        $this->customerRepository = $customerRepository;
        $this->websiteRepository = $websiteRepository;
        $this->postReserver = $postReserver;
    }

    public function behaviors(): array
    {
        return array_replace(parent::behaviors(), [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                    'activate' => ['POST'],
                    'block' => ['POST'],
                ],
            ],
        ]);
    }

    public function actionIndex()
    {
        $searchModel = new WebsiteSearch($this->websiteRepository, $this->customerRepository);
        $dataProvider = $searchModel->search(Yii::$app->request->get());

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        try {
            $website = $this->websiteRepository->get($id);
            return $this->render('view', [
                'model' => $website,
            ]);
        } catch (NotFoundException $e) {
            return $this->redirect('index');
        }
    }

    public function actionCreate()
    {
        $form = new CreateForm($this->websiteRepository, $this->postReserver);

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->websiteService->create($form);
                return $this->redirect(['index']);
            } catch (RuntimeException | Exception $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('danger', $e->getMessage());
            }
        }

        return $this->render('create', ['model' => $form]);
    }

    public function actionDomains($q, $id = null)
    {
        $response = [];
        $exclude = $id ? ['exclude' => $id] : [];
        foreach ($this->websiteRepository->getDomains($q, $exclude) as $website)
            $response['results'][] = ['id' => $website->id, 'text' => $website->name];
        return $this->asJson($response);
    }

    public function actionUpdate($id)
    {
        try {
            $website = $this->websiteRepository->get($id);
        } catch (NotFoundException $e) {
            return $this->redirect('index');
        }

        $form = new UpdateForm($website, $this->websiteRepository, $this->postReserver);

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->websiteService->edit($website, $form);
                return $this->redirect(['update', 'id' => $id]);
            } catch (DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('danger', $e->getMessage());
            }
        }

        return $this->render('update', [
            'model' => $form,
            'website' => $website
        ]);
    }

    public function actionActivate($id)
    {
        try {
            $this->websiteService->activate($id);
        } catch (NotFoundException | Exception $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('danger', $e->getMessage());
        }

        return $this->redirect(['index']);
    }

    public function actionBlock($id)
    {
        try {
            $this->websiteService->block($id);
        } catch (NotFoundException | Exception $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('danger', $e->getMessage());
        }

        return $this->redirect(['index']);
    }

    public function actionDelete($id)
    {
        try {
            $this->websiteService->delete($id);
        } catch (NotFoundException | Exception $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('danger', $e->getMessage());
        }

        return $this->redirect(['index']);
    }
}
