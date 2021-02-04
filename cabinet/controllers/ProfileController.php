<?php

namespace cabinet\controllers;

use Yii;
use yii\web\Controller;
use yii\web\UploadedFile;
use core\entities\Customer\Customer;
use core\forms\cabinet\Profile\ChangePasswordForm;
use core\forms\cabinet\Profile\UpdateForm;
use core\useCases\cabinet\ProfileService;

/**
 * Class ProfileController
 * @package cabinet\controllers
 */
class ProfileController extends Controller
{
    private ProfileService $profileService;

    public function __construct(string $id, $module, ProfileService $profileService, array $config = [])
    {
        parent::__construct($id, $module, $config);

        $this->profileService = $profileService;
    }

    public function actionIndex()
    {
        /** @var Customer $customer */
        $customer = Yii::$app->user->identity;
        $form = new UpdateForm($customer);
        if ($form->load(Yii::$app->request->post())) {
            $form->thumbFile = UploadedFile::getInstance($form, 'thumbFile');
            if ($form->validate()) {
                try {
                    $this->profileService->update($customer->id, $form);
                    Yii::$app->session->setFlash('success', 'Dəyişiliklər yadda saxlanıldı');
                } catch (\DomainException $e) {
                    Yii::$app->session->setFlash('error', Yii::t('profile', $e->getMessage()));
                }
            }
        }
        return $this->render('index', [
            'customer' => $customer,
            'model' => $form,
        ]);
    }

    public function actionChangePassword()
    {
        /** @var Customer $customer */
        $customer = Yii::$app->user->identity;
        $form = new ChangePasswordForm($customer);
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->profileService->changePassword($customer->id, $form);
                Yii::$app->session->setFlash('success', 'Şifrə dəyişildi');
                return $this->redirect(['index']);
            } catch (\DomainException $e) {
                Yii::$app->session->setFlash('error', Yii::t('profile', $e->getMessage()));
            }
        }
        return $this->render('change-password', ['model' => $form]);
    }
}