<?php

namespace cabinet\controllers\auth;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use core\useCases\auth\SignUpService;
use core\forms\auth\cabinet\SignUpForm;

class SignupController extends Controller
{
    public $layout = 'login';

    private $service;

    public function __construct($id, $module, SignUpService $service, $config = [])
    {
        $this->service = $service;
        parent::__construct($id, $module, $config);
    }

    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['request'],
                'rules' => [
                    [
                        'actions' => ['request'],
                        'allow' => true,
                    ],
                ],
            ],
        ];
    }

    /**
     * @return mixed
     */
    public function actionRequest()
    {
        $form = new SignUpForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->requestSignUp($form);
                //return $this->render('success');
                Yii::$app->session->setFlash('success', Yii::t('signup', 'account_created'));
                return $this->redirect(['auth/auth/login']);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', Yii::t('errors', $e->getMessage()));
            }
        }

        return $this->render('index', [
            'model' => $form,
        ]);
    }

    /**
     * @param $token
     * @return mixed
     */
    public function actionConfirm($token)
    {
        try {
            $this->service->confirm($token);
            Yii::$app->session->setFlash('success', Yii::t('signup', 'account_confirmed'));
        } catch (\DomainException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', Yii::t('errors', $e->getMessage()));
            return $this->redirect(['request']);
        }

        return $this->redirect(['auth/auth/login']);
    }
}