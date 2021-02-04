<?php

namespace backend\controllers;

use Yii;
use Exception;
use DomainException;
use core\forms\ChartForm;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use backend\models\SiteSearch;
use core\useCases\auth\AuthService;
use core\forms\auth\backend\LoginForm;
use core\entities\Customer\Website\Website;
use core\services\statistics\TrafficCalculator;
use core\repositories\interfaces\WebsiteRepositoryInterface;

/**
 * Class SiteController
 * @package backend\controllers
 */
class SiteController extends BaseAdminController
{
    private AuthService $authService;
    private WebsiteRepositoryInterface $websiteRepository;
    private TrafficCalculator $trafficCalculator;

    public function __construct(
        string $id,
        $module,
        AuthService $authService,
        TrafficCalculator $trafficCalculator,
        WebsiteRepositoryInterface $websiteRepository,
        array $config = []
    )
    {
        parent::__construct($id, $module, $config);

        $this->authService = $authService;
        $this->websiteRepository = $websiteRepository;
        $this->trafficCalculator = $trafficCalculator;
    }

    public function behaviors()
    {
        $params = [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];

        return array_replace(parent::behaviors(), $params);
    }

    public function actions()
    {
        return [
            'error' => ['class' => 'yii\web\ErrorAction'],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new SiteSearch($this->websiteRepository, $this->trafficCalculator);
        $searchModel->search(Yii::$app->request->get());

        return $this->render('index', [
            'websites' => ArrayHelper::map($this->websiteRepository->all(['status' => Website::STATUS_ACTIVE]), 'id', 'name'),
            'inTraffic' => $searchModel->getInTraffic(),
            'outTraffic' =>  $searchModel->getOutTraffic(),
            'searchModel' => $searchModel,
        ]);
    }

    public function actionChart()
    {
        $form = new ChartForm();
        $form->load(Yii::$app->request->get());
        if ($form->validate())
            return $this->asJson(['status' => $form->rememberLegends(Yii::$app->response, Yii::$app->request)]);
        return $this->asJson(['status' => false]);
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest)
            return $this->goHome();

        $form = new LoginForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $user = $this->authService->auth($form);
                Yii::$app->user->login($user, $form->rememberMe ? Yii::$app->params['user.sessionDurationExpiry'] : 0);
                return $this->goBack();
            } catch (DomainException $e) {
                Yii::$app->session->setFlash('error', $e->getMessage());
                //Yii::$app->errorHandler->logException($e);
            } catch (Exception $e) {
                Yii::$app->errorHandler->logException($e);
                return $this->redirect(['site/error']);
            }
        }

        $this->layout = 'main-login';
        return $this->render('login', [
            'model' => $form,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->redirect(['login']);
    }
}
