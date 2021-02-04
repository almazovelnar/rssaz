<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use frontend\models\FilterForm;
use core\entities\Customer\Website\Post;
use core\repositories\interfaces\PostRepositoryInterface;

/**
 * Class SiteController
 * @package frontend\controllers
 */
class SiteController extends Controller
{
    private PostRepositoryInterface $postRepository;
    private ?array $priorityPostTypes = [];

    /**
     * SiteController constructor.
     * @param string $id
     * @param $module
     * @param PostRepositoryInterface $postRepository
     * @param Post $post
     * @param array $config
     */
    public function __construct(
        string $id,
        $module,
        PostRepositoryInterface $postRepository,
        Post $post,
        array $config = []
    )
    {
        parent::__construct($id, $module, $config);

        $this->postRepository = $postRepository;
        $this->priorityPostTypes = $post->getPriorityPostTypes();
        shuffle($this->priorityPostTypes);
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => 'yii\filters\AjaxFilter',
                'only' => ['filter']
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $request = Yii::$app->request;
        $form = new FilterForm();
        $params = $form->getFilters($request);

        if ($this->postRepository->getPostsCount(30, $form->getFilters($request)) < 16)
            $params = $form->getDefaultPeriodParams();

        $posts = $this->postRepository->getInnerPopular(30, $params);
        shuffle($posts);

        return $this->render('index', [
            'posts' => array_slice($posts, 0,16),
            'otherPosts' => $this->postRepository->all(12),
            'actualPosts' => $this->postRepository->all(30),
            'defaultPeriod' => $form->getDefaultPeriod(),
            'priorityPostTypes' => $this->priorityPostTypes,
            'highlightedTypes' => $this->priorityPostTypes
        ]);
    }

    /**
     * Filtering News.
     *
     * @return string
     */
    public function actionFilter()
    {
        $form = new FilterForm();
        if ($form->load(Yii::$app->request->get()) && $form->validate())
            return $this->asJson(['status' => $form->rememberFilters(Yii::$app->response)]);
        return $this->asJson(['status' => false]);
    }
}
