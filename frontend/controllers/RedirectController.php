<?php

namespace frontend\controllers;

use Yii;
use core\forms\ClickForm;
use core\helpers\RequestHelper;
use yii\web\{Controller,Response};
use core\events\{Click, OwnClick};
use core\dispatchers\EventDispatcher;
use core\exceptions\NotFoundException;
use core\entities\Customer\Website\Post;
use core\repositories\interfaces\PostRepositoryInterface;

/**
 * Class RedirectController
 * @package frontend\controllers
 */
class RedirectController extends Controller
{
    private PostRepositoryInterface $postRepository;
    private EventDispatcher $eventDispatcher;
    private ?array $priorityPostTypes;

    /**
     * RedirectController constructor.
     * @param string $id
     * @param $module
     * @param PostRepositoryInterface $postRepository
     * @param EventDispatcher $eventDispatcher
     * @param Post $post
     * @param array $config
     */
    public function __construct
    (
        string $id,
        $module,
        Post $post,
        EventDispatcher $eventDispatcher,
        PostRepositoryInterface $postRepository,
        array $config = []
    )
    {
        parent::__construct($id, $module, $config);

        $this->postRepository = $postRepository;
        $this->eventDispatcher = $eventDispatcher;
        $this->priorityPostTypes = $post->getPriorityPostTypes();
        shuffle($this->priorityPostTypes);
    }

    /**
     * @param int $id
     * @return string|Response
     */
    public function actionView(int $id)
    {
        try {
            $post = $this->postRepository->get($id, ['status' => Post::STATUS_ACTIVE]);
        } catch (NotFoundException $e) {
            return $this->redirect(['site/index']);
        }

        $request = Yii::$app->request;
        $form = new ClickForm();
        if ($form->load($request->get()) && $form->validate()) {
            if (!RequestHelper::isPageRefreshed())
                $this->eventDispatcher->dispatch(new Click($post, $form, $request->getUserIP()));
        } else {
            $this->eventDispatcher->dispatch(new OwnClick($post, $request->getReferrer()));
            return $this->redirect($post->link);
        }

        return $this->render('view', [
            'post' => $post,
            'sid' => $form->sid,
            'otherPosts' => $this->postRepository->all(28),
            'priorityPostTypes' => $this->priorityPostTypes,
            'posts' => $this->postRepository->getAllExcludeOne($post->getId(), 12),
            'nextPost' => $this->postRepository->getRandomNextPostByCategory($post->category_id, $post->getId()) ?: $post,
            'similarPosts' => $this->getSimilarPosts($post, 3)
        ]);
    }

    private function getSimilarPosts(Post $post, int $days): ?array
    {
        $days = $this->postRepository->getPostsCountByCategoryExcludeOneInDays($post->category_id, $post->id, 10, $days) > 7 ? $days : Post::REDIRECT_SIMILAR_POSTS_DAYS;

        return $this->postRepository->getByCategoryExcludeOneInDays($post->category_id, $post->getId(), 10, $days);
    }
}