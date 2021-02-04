<?php

namespace cabinet\controllers;

use Yii;
use Exception;
use yii\web\Controller;
use core\validators\DOMValidator;
use core\forms\cabinet\ValidatorForm;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Class ValidatorController
 * @package cabinet\controllers
 */
class ValidatorController extends Controller
{
    private DOMValidator $domValidator;
    private ValidatorForm $validatorForm;

    public function __construct(
        $id,
        $module,
        DOMValidator $domValidator,
        ValidatorForm $validatorForm,
        $config = []
    )
    {
        parent::__construct($id, $module, $config);

        $this->domValidator = $domValidator;
        $this->validatorForm = $validatorForm;
    }

    public function actionIndex()
    {
        $errors = [];
        $proceeded = false;
        if ($this->validatorForm->load(Yii::$app->request->get()) && $this->validatorForm->validate()) {
            if (!$this->domValidator->validateFeeds($this->validatorForm->getLink())) {
                foreach ($this->domValidator->getErrors() as $error) {
                    $errors[] = $error;
                    if ($error->level == 1) break;
                }
            }
            $proceeded = true;
        }

        return $this->render('index', [
            'model' => $this->validatorForm,
            'errors' => $errors,
            'proceeded' => $proceeded,
        ]);
    }
}