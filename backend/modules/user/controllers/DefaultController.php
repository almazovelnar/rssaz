<?php

namespace backend\modules\user\controllers;

use Yii;
use core\entities\User;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\components\auth\Rbac;
use yii\web\NotFoundHttpException;
use core\useCases\manager\UserService;
use core\forms\manager\PasswordUpdateForm;
use backend\modules\user\models\UserSearch;
use backend\controllers\BaseAdminController;
use core\forms\manager\User\{CreateForm, UpdateForm};

/**
 * DefaultController implements the CRUD actions for User model.
 */
class DefaultController extends BaseAdminController
{
    private UserService $userService;

    public function behaviors(): array
    {
        $params = [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
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

    public function __construct(
        string $id,
        $module,
        UserService $userService,
        array $config = []
    )
    {
        $this->userService = $userService;

        parent::__construct($id, $module, $config);
    }

    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate()
    {
        $form = new CreateForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->userService->create($form);
                return $this->redirect(['index']);
            } catch (\DomainException $e) {
                Yii::$app->session->setFlash('error', $e->getMessage());
                Yii::$app->errorHandler->logException($e);
            }
        }

        return $this->render('create', [
            'model' => $form,
        ]);
    }

    public function actionUpdate($id)
    {
        $user = $this->findModel($id);
        $form = new UpdateForm($user);

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->userService->update($user->id, $form);
                Yii::$app->session->setFlash('success', 'İstifadəçi məlumatları yeniləndi !');
                return $this->redirect(['update', 'id' => $id]);
            } catch (\DomainException $e) {
                Yii::$app->session->setFlash('error', $e->getMessage());
                Yii::$app->errorHandler->logException($e);
            }
        }

        return $this->render('update', [
            'model' => $form,
            'user'  => $user,
        ]);
    }

    public function actionUpdatePassword(int $id)
    {
        $user = $this->findModel($id);
        $form = new PasswordUpdateForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->userService->updatePassword($user->id, $form);
                Yii::$app->session->setFlash('success', 'Şifrə uğurla yeniləndi!');
                return $this->redirect(['index']);
            } catch (\DomainException $e) {
                Yii::$app->session->setFlash('error', $e->getMessage());
                Yii::$app->errorHandler->logException($e);
            }
        }
        return $this->render('update-password', [
            'model' => $form,
            'user' => $user
        ]);
    }

    public function actionDelete($id)
    {
        $user = $this->findModel($id);

        try {
            $this->userService->delete($user->id);
            Yii::$app->session->setFlash('success', 'İstifadəçi uğurla silindi !');
        } catch (\DomainException $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
            Yii::$app->errorHandler->logException($e);
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
