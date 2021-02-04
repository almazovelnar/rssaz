<?php

namespace core\repositories\interfaces;

use core\entities\Image;
use core\queries\ImageQuery;

/**
 * Interface ImageRepositoryInterface
 * @package core\repositories\interfaces
 */
interface ImageRepositoryInterface
{
    public function getByHash(array $filter = []);
    public function query(array $select = []): ImageQuery;
    public function save(Image $image): Image;
}