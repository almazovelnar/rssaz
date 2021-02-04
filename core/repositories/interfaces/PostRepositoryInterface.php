<?php

namespace core\repositories\interfaces;

use core\queries\PostQuery;
use core\exceptions\NotFoundException;

/**
 * Interface PostRepositoryInterface
 * @package core\repositories\interfaces
 */
interface PostRepositoryInterface
{
    /**
     * @param int $id
     * @param array $filters
     * @throws NotFoundException
     */
    public function get(int $id, array $filters = []);
    public function getByIds(array $ids, array $select = []);
    public function query(array $select = []): PostQuery;
}