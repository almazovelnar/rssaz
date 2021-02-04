<?php

namespace core\repositories\interfaces;

use core\entities\Customer\Website\PostDuplicateReason;
use core\exceptions\NotFoundException;

/**
 * Interface PostDuplicateReasonRepositoryInterface
 * @package core\repositories\interfaces
 */
interface PostDuplicateReasonRepositoryInterface
{
    /**
     * @param int $id
     * @return PostDuplicateReason
     * @throws NotFoundException
     */
    public function get(int $id): PostDuplicateReason;
    public function save(PostDuplicateReason $postDuplicateReason): PostDuplicateReason;
    public function remove(PostDuplicateReason $postDuplicateReason): bool;
}