<?php

namespace backend\modules\customer\controllers;

use Yii;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use core\entities\Customer\Customer;
use core\repositories\CustomerRepository;
use core\forms\manager\PasswordUpdateForm;
use core\useCases\manager\CustomerService;
use backend\controllers\BaseAdminController;
use backend\modules\customer\models\CustomerSearch;
use core\forms\manager\Customer\{CreateForm, UpdateForm};

/**
 * Class DefaultController
 * @package backend\modules\customer\controllers
 */
class DefaultController extends BaseAdminController
{
    private CustomerService $customerService;
    private CustomerRepository $customerRepository;

    public function __construct(
        string $id,
        $module,
        CustomerService $customerService,
        CustomerRepository $customerRepository,
        array $config = []
    )
    {
        $this->customerService = $customerService;
        $this->customerRepository = $customerRepository;
        parent::__construct($id, $module, $config);
    }

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

    public function actionIndex()
    {
        $searchModel = new CustomerSearch();
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

    /**
     * @param $q
     * @return \yii\web\Response
     */
    public function actionList($q)
    {
        return $this->asJson($this->customerRepository->getList($q));
    }

    public function actionCreate()
    {
        $form = new CreateForm();

        if ($form->load(Yii::$app->request->post())) {
            $form->thumbFile = UploadedFile::getInstance($form, 'thumbFile');
            if ($form->validate()) {
                try {
                    $this->customerService->create($form);
                    return $this->redirect(['index']);
                } catch (\DomainException $e) {
                    Yii::$app->errorHandler->logException($e);
                    Yii::$app->session->setFlash('danger', $e->getMessage());
                }
            }
        }

        return $this->render('create', [
            'model' => $form,
        ]);
    }

    public function actionUpdate($id)
    {
        $customer = $this->findModel($id);
        $form = new UpdateForm($customer);

        if ($form->load(Yii::$app->request->post())) {
            $form->thumbFile = UploadedFile::getInstance($form, 'thumbFile');
            if ($form->validate()) {
                try {
                    $this->customerService->edit($id, $form);
                    return $this->redirect(['index']);
                } catch (\DomainException $e) {
                    Yii::$app->errorHandler->logException($e);
                    Yii::$app->session->setFlash('danger', $e->getMessage());
                }
            }
        }

        return $this->render('update', [
            'model' => $form,
            'customer' => $customer,
        ]);
    }

    public function actionUpdatePassword(int $id)
    {
        $customer = $this->findModel($id);
        $form = new PasswordUpdateForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->customerService->updatePassword($customer->id, $form);
                Yii::$app->session->setFlash('success', 'Password updated');
                return $this->redirect(['index']);
            } catch (\DomainException $e) {
                Yii::$app->session->setFlash('error', $e->getMessage());
                Yii::$app->errorHandler->logException($e);
            }
        }
        return $this->render('update-password', [
            'model' => $form,
            'customer' => $customer
        ]);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Customer model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Customer the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Customer::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
