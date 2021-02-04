<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use core\forms\SearchForm;
use yii\web\BadRequestHttpException;
use core\services\SearchService;

/**
 * Class SearchController
 * @package frontend\controllers
 */
class SearchController extends Controller
{
    private SearchService $searchService;

    /**
     * SearchController constructor.
     * @param string $id
     * @param $module
     * @param SearchService $searchService
     * @param array $config
     */
    public function __construct(
        string $id,
        $module,
        SearchService $searchService,
        array $config = []
    )
    {
        parent::__construct($id, $module, $config);

        $this->searchService = $searchService;
    }

    /**
     * @return string
     * @throws BadRequestHttpException
     */
    public function actionIndex()
    {
        $form = new SearchForm();
        $request = Yii::$app->request;

        if (!$form->load($request->get()) || !$form->validate())
            throw new BadRequestHttpException();

        $posts = $this->searchService->search($form);

        if ($request->isAjax) {
            return $this->asJson([
                'html' => $this->renderPartial('partials/_post_block', ['posts' => $posts]),
                'limitReached' => count($posts) < $form->getLimit(),
            ]);
        }

        return $this->render('index', [
            'searchModel' => $form,
            'posts' => $posts,
            'searchQuery' => $form->q,
        ]);
    }
}