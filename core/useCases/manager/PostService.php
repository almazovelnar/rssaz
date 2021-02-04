<?php

namespace core\useCases\manager;

use core\services\PostIndexer;
use core\exceptions\NotFoundException;
use core\entities\Customer\Website\Post;
use core\components\Storage\directories\PostDirectory;
use core\repositories\interfaces\{PostRepositoryInterface, ImageRepositoryInterface};

class PostService
{
    private PostRepositoryInterface $postRepository;
    private ImageRepositoryInterface $imageRepository;
    private PostDirectory $postDirectory;
    private PostIndexer $postIndexer;

    public function __construct(
        PostIndexer $postIndexer,
        PostDirectory $postDirectory,
        PostRepositoryInterface $postRepository,
        ImageRepositoryInterface $imageRepository
    )
    {
        $this->postIndexer = $postIndexer;
        $this->postDirectory = $postDirectory;
        $this->postRepository = $postRepository;
        $this->imageRepository = $imageRepository;
    }

    /**
     * @param int $id
     * @throws NotFoundException
     */
    public function delete(int $id)
    {
        $post = $this->postRepository->get($id);
        /** @var Post $post */
        $this->postRepository->remove($post);
        $this->postIndexer->remove($post->getId());

        if ($this->postRepository->getPostsCountByImage(['image' => $post->getImage(), 'excludeOne' => $post->getId()]) === 0) {
            $this->imageRepository->remove($this->imageRepository->getByFilename($post->getImage()));
            $this->postDirectory->remove($post->getImage());
        }
    }
}