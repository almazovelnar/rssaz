<?php

namespace cabinet\controllers\auth;

use Yii;
use DomainException;
use yii\web\Controller;
use yii\filters\AccessControl;
use core\useCases\auth\AuthService;
use core\forms\auth\cabinet\LoginForm;

class AuthController extends Controller
{
    public $layout = 'login';
    private $authService;

    public function __construct($id, $module, AuthService $authService, $config = [])
    {
        parent::__construct($id, $module, $config);

        $this->authService = $authService;
    }

    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['login'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionLogin()
    {
        $form = new LoginForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->authService->auth($form);
                return $this->goBack();
            } catch (DomainException $e) {
                // Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', Yii::t('errors', $e->getMessage()));
            }
        }

        return $this->render('index', [
            'model' => $form,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->redirect(['login']);
    }
}