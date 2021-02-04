<?php

namespace core\repositories;

use core\exceptions\NotFoundException;
use Throwable;
use RuntimeException;
use core\entities\Customer\Website\PostDuplicateReason;
use core\repositories\interfaces\PostDuplicateReasonRepositoryInterface;

/**
 * Class PostDuplicateReasonRepository
 * @package core\repositories
 */
class PostDuplicateReasonRepository implements PostDuplicateReasonRepositoryInterface
{
    /**
     * @param int $id
     * @return PostDuplicateReason
     * @throws NotFoundException
     */
    public function get(int $id): PostDuplicateReason
    {
        if (($reason = PostDuplicateReason::findOne(['id' => $id])) === null)
            throw new NotFoundException("Record not found");
        return $reason;
    }

    /**
     * @param PostDuplicateReason $postDuplicateReason
     * @return PostDuplicateReason
     */
    public function save(PostDuplicateReason $postDuplicateReason): PostDuplicateReason
    {
        if (!$postDuplicateReason->save())
            throw new RuntimeException("Can't save record.");
        return $postDuplicateReason;
    }

    /**
     * @param PostDuplicateReason $postDuplicateReason
     * @return bool
     * @throws Throwable
     */
    public function remove(PostDuplicateReason $postDuplicateReason): bool
    {
        return $postDuplicateReason->delete();
    }
}