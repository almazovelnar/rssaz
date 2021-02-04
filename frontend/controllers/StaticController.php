<?php

namespace frontend\controllers;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use core\readModels\PageReadRepository;

/**
 * Class StaticController
 * @package frontend\controllers
 */
class StaticController extends Controller
{
    private PageReadRepository $pages;

    public function __construct(string $id, $module, PageReadRepository $pages, array $config = [])
    {
        parent::__construct($id, $module, $config);

        $this->pages = $pages;
    }

    public function actionView($slug)
    {
        if (($page = $this->pages->getBySlug($slug)) == null) {
            throw new NotFoundHttpException('Page not found');
        }

        return $this->render('view', ['page' => $page]);
    }
}