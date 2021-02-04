<?php

namespace cabinet\controllers;

use Yii;
use Exception;
use DOMDocument;
use DomainException;
use RuntimeException;
use yii\widgets\ActiveForm;
use yii\filters\VerbFilter;
use core\validators\DOMValidator;
use yii\web\{Controller, Response};
use core\exceptions\NotFoundException;
use core\useCases\cabinet\WebsiteService;
use core\forms\cabinet\Website\{CreateForm, UpdateForm};
use core\repositories\interfaces\WebsiteRepositoryInterface;

/**
 * Class WebsiteController
 * @package cabinet\controllers
 */
class WebsiteController extends Controller
{
    private WebsiteService $websiteService;
    private WebsiteRepositoryInterface $websiteRepository;
    private DOMValidator $domValidator;

    public function __construct(
        string $id,
        $module,
        WebsiteService $websiteService,
        WebsiteRepositoryInterface $websiteRepository,
        DOMValidator $domValidator,
        array $config = []
    )
    {
        parent::__construct($id, $module, $config);

        $this->websiteService = $websiteService;
        $this->websiteRepository = $websiteRepository;
        $this->domValidator = $domValidator;
    }

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                    'validateRss' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index', [
            'websites' => $this->websiteRepository->getByCustomer(Yii::$app->user->id),
        ]);
    }

    /**
     * @return string|Response
     * @throws Exception
     */
    public function actionCreate()
    {
        $form = new CreateForm($this->websiteRepository);

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->websiteService->register($form);
                return $this->redirect(['index']);
            } catch (RuntimeException | Exception $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', Yii::t('errors', $e->getMessage()));
            }
        }

        return $this->render('create', ['model' => $form]);
    }

    /**
     * @param $id
     * @return string|Response
     * @throws Exception
     */
    public function actionUpdate(int $id)
    {
        try {
            $website = $this->websiteRepository->get($id);
        } catch (NotFoundException $e) {
            return $this->redirect(['index']);
        }

        $form = new UpdateForm($website);

        $request = Yii::$app->request;
        if ($request->isAjax && $form->load($request->post()))
            return $this->asJson(ActiveForm::validate($form));

        if ($form->load($request->post()) && $form->validate()) {
            try {
                $this->websiteService->edit($website, $form);
                return $this->redirect(['update', 'id' => $website->id]);
            } catch (DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', Yii::t('errors', $e->getMessage()));
            }
        }

        return $this->render('update', [
            'model' => $form,
            'website' => $website,
        ]);
    }

    public function actionValidateRss(string $language)
    {
        $response = $this->domValidator->validateFeeds(Yii::$app->request->post('rss'), $language)
            ? ['success' => true, 'message' => 'Rss is valid']
            : ['success' => false, 'message' => 'Rss is not valid'];

        return $this->asJson($response);
    }

    public function actionDomains($id, $q)
    {
        $response = [];
        foreach ($this->websiteRepository->getDomains($q, ['exclude' => $id]) as $website)
            $response['results'][] = ['id' => $website->id, 'text' => $website->name];
        return $this->asJson($response);
    }

    /**
     * @param $id
     * @return Response
     */
    public function actionDelete($id)
    {
        try {
            $this->websiteService->delete($id);
        } catch (NotFoundException | Exception $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', Yii::t('errors', $e->getMessage()));
        }
        return $this->redirect(['index']);
    }
}
