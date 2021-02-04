<?php

namespace backend\modules\website\controllers;

use Yii;
use DomainException;
use core\entities\Customer\Website\Post;
use core\exceptions\NotFoundException;
use backend\controllers\BaseAdminController;
use backend\modules\website\models\DuplicatedPostSearch;
use core\repositories\interfaces\PostRepositoryInterface;
use core\repositories\interfaces\PostDuplicateReasonRepositoryInterface;

/**
 * Class DuplicatedNewsController
 * @package backend\modules\website\controllers
 */
class DuplicatedNewsController extends BaseAdminController
{

    private PostRepositoryInterface $postRepository;
    private PostDuplicateReasonRepositoryInterface $duplicateReasonRepository;

    public function __construct(
        $id,
        $module,
        PostRepositoryInterface $postRepository,
        PostDuplicateReasonRepositoryInterface $duplicateReasonRepository,
        $config = []
    )
    {
        parent::__construct($id, $module, $config);

        $this->postRepository = $postRepository;
        $this->duplicateReasonRepository = $duplicateReasonRepository;
    }

    public function actionIndex()
    {
        $searchModel = new DuplicatedPostSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->get());

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionActivate(int $id, int $duplicateId)
    {
        try {
            $post = $this->postRepository->get($duplicateId);
            if (!$post->isDuplicated()) throw new DomainException("Post is not duplicated.");
            $this->postRepository->changeStatus($post, Post::STATUS_ACTIVE);
            $this->duplicateReasonRepository->remove($this->duplicateReasonRepository->get($id));
            return $this->redirect('index');
        } catch (NotFoundException | DomainException $e) {
            return $this->redirect('index');
        }
    }

    public function actionCompare(int $id)
    {
        try {
            $reason = $this->duplicateReasonRepository->get($id);
            return $this->render('compare', [
                'original'   => $this->postRepository->get($reason->getOriginalPostId()),
                'duplicated' => $this->postRepository->get($reason->getDuplicatedPostId()),
            ]);
        } catch (NotFoundException $e) {
            return $this->redirect('index');
        }
    }
}