<?php

namespace core\useCases\manager;

use Yii;
use core\services\PostIndexer;
use core\forms\PostRemovalReasonForm;
use core\entities\Customer\Website\Post;
use core\repositories\PostRemovalReasonRepository;
use core\entities\Customer\Website\PostRemovalReason;
use core\repositories\interfaces\PostRepositoryInterface;

class PostRemovalReasonService
{
    private PostRemovalReasonRepository $postRemovalRepository;
    private PostRepositoryInterface $postRepository;
    private PostIndexer $postIndexer;
    private ?int $user = null;

    public function __construct(
        PostRemovalReasonRepository $postRemovalRepository,
        PostRepositoryInterface $postRepository,
        PostIndexer $postIndexer
    )
    {
        $this->postRemovalRepository = $postRemovalRepository;
        $this->postRepository = $postRepository;
        $this->postIndexer = $postIndexer;
        $this->user = Yii::$app->user->id;
    }

    public function create(Post $post, PostRemovalReasonForm $form): bool
    {
        $postRemovalReason = PostRemovalReason::create($post->id, $this->user, $form->reason);
        $this->postRemovalRepository->save($postRemovalReason);
        $this->postIndexer->remove($post->id);
        return $this->postRepository->changeStatus($post, Post::STATUS_MODERATE);
    }

    public function activate(int $id)
    {
        $reason = $this->postRemovalRepository->getBy(['id' => $id]);
        $post = Post::findOne($reason->post_id);
        $post->editStatus(Post::STATUS_ACTIVE);
        $this->postRepository->changeStatus($post, Post::STATUS_ACTIVE);
        $this->postRemovalRepository->remove($reason);
        $this->postIndexer->index($post);
    }

    public function delete(int $id)
    {
        $reason = $this->postRemovalRepository->getBy(['id' => $id]);
        $this->postRepository->remove(Post::findOne($reason->post_id));
        $this->postRemovalRepository->remove($reason);
    }
}