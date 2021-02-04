<?php

namespace cabinet\controllers;

use yii\web\Controller;
use core\readModels\PageReadRepository;

class StaticController extends Controller
{
    private PageReadRepository $pageReadRepository;

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function __construct($id, $module, PageReadRepository $pageReadRepository, $config = [])
    {
        parent::__construct($id, $module, $config);

        $this->pageReadRepository = $pageReadRepository;
    }

    public function actionRequirements()
    {
        return $this->render('requirements', [
            'requirements' => $this->pageReadRepository->getPageBySlugWithTranslations('requirements')
        ]);
    }
}