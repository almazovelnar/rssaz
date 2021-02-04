<?php

namespace cabinet\controllers\auth;

use core\forms\auth\cabinet\PasswordResetRequestForm;
use core\forms\auth\cabinet\PasswordResetForm;
use core\useCases\auth\PasswordResetService;
use Yii;
use yii\web\BadRequestHttpException;
use yii\web\Controller;

class ResetController extends Controller
{
    public $layout = 'login';

    private $service;

    public function __construct($id, $module, PasswordResetService $service, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
    }

    public function actionRequest()
    {
        $form = new PasswordResetRequestForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->request($form);
                Yii::$app->session->setFlash('success', Yii::t('reset', 'reset_request_sent'));
                return $this->redirect(['auth/auth/login']);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', Yii::t('errors', $e->getMessage()));
            }
        }

        return $this->render('request', ['model' => $form]);
    }

    public function actionConfirm($token)
    {
        try {
            $this->service->validateToken($token);
        } catch (\DomainException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        $form = new PasswordResetForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->reset($token, $form);
                Yii::$app->session->setFlash('success', Yii::t('reset', 'reset_success'));
                return $this->redirect(['auth/auth/login']);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', Yii::t('errors', $e->getMessage()));
            }
            return $this->redirect(['auth/auth/login']);
        }

        return $this->render('reset', ['model' => $form]);
    }
}