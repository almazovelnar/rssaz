<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use frontend\models\DatetimeForm;
use yii\web\NotFoundHttpException;
use core\readModels\CategoryReadRepository;
use core\repositories\interfaces\PostRepositoryInterface;


/**
 * Class CategoryController
 * @package frontend\controllers
 */
class CategoryController extends Controller
{
    private const LIMIT = 24;

    private CategoryReadRepository $categoryReadRepository;
    private PostRepositoryInterface $postRepository;

    /**
     * CategoryController constructor.
     * @param string $id
     * @param $module
     * @param CategoryReadRepository $categoryReadRepository
     * @param PostRepositoryInterface $postRepository
     * @param array $config
     */
    public function __construct(
        string $id,
        $module,
        CategoryReadRepository $categoryReadRepository,
        PostRepositoryInterface $postRepository,
        array $config = []
    )
    {
        parent::__construct($id, $module, $config);

        $this->categoryReadRepository = $categoryReadRepository;
        $this->postRepository = $postRepository;
    }

    /**
     * @param $slug
     * @param null $page
     * @param string|null $date
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionView($slug, $page = null, ?string $date = null)
    {
        if (($category = $this->categoryReadRepository->getBySlug($slug)) === null)
            throw new NotFoundHttpException('Category not found !');

        $filters = ['category' => $category->id];
        if (!empty($page)) $filters['page'] = $page;
        if ($date) $filters['lastParsed'] = $date;

        $posts = $this->postRepository->all(self::LIMIT, $filters);

        if (Yii::$app->request->isAjax) {
            return $this->asJson([
                'html'  => $this->renderPartial('partials/_category_block', compact('posts')),
                'limitReached' => (count($posts) < self::LIMIT)
            ]);
        }

        return $this->render('view', compact('category', 'posts'));
    }

    /**
     * @param string|null $date
     * @param int|null $category
     * @return \yii\web\Response
     */
    public function actionList(?string $date = null, ?int $category = null)
    {
        $form = new DatetimeForm(['date' => $date]);

        if ($form->validate() && isset($category)) {
            $latest = $this->postRepository->all(self::LIMIT, ['minDate' => $date, 'category' => $category]);
            return $this->asJson([
                'html'  => $this->renderPartial('partials/_category_block', ['posts' => $latest]),
                'limitReached' => (count($latest) < self::LIMIT)
            ]);
        }
    }
}