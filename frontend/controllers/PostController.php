<?php

namespace frontend\controllers;

use Yii;
use frontend\models\DatetimeForm;
use yii\web\{Controller, Response};
use core\repositories\interfaces\PostRepositoryInterface;

/**
 * Class PostController
 * @package frontend\controllers
 */
class PostController extends Controller
{
    private const LIMIT = 24;
    private PostRepositoryInterface $postRepository;

    /**
     * PostController constructor.
     * @param string $id
     * @param $module
     * @param PostRepositoryInterface $postRepository
     * @param array $config
     */
    public function __construct(
        string $id,
        $module,
        PostRepositoryInterface $postRepository,
        array $config = []
    )
    {
        parent::__construct($id, $module, $config);

        $this->postRepository = $postRepository;
    }

    /**
     * @param int|null $page
     * @param string|null $date
     * @return string|Response
     */
    public function actionIndex(?int $page = null, ?string $date = null)
    {
        $filters = !empty($page) ? ['page' => $page] : [];
        if ($date) $filters['lastParsed'] = $date;

        $posts = $this->postRepository->all(self::LIMIT, $filters);

        if (Yii::$app->request->isAjax) {
            return $this->asJson([
                'html'  => $this->renderPartial('partials/_post_block', compact('posts')),
                'limitReached' => (count($posts) < self::LIMIT)
            ]);
        }

        return $this->render('index', compact('posts'));
    }

    /**
     * @param string|null $date
     * @return Response
     */
    public function actionList(?string $date = null)
    {
        $form = new DatetimeForm(['date' => $date]);

        if ($form->validate()) {
            $latest = $this->postRepository->all(self::LIMIT, ['minDate' => $form->date]);
            return $this->asJson([
                'html'  => $this->renderPartial('partials/_post_block', ['posts' => $latest]),
                'limitReached' => (count($latest) < self::LIMIT)
            ]);
        }
    }
}