<?php

namespace core\repositories\interfaces;

use core\queries\AlgorithmQuery;
use core\entities\Customer\Website\Website;

/**
 * Interface AlgorithmRepositoryInterface
 * @package core\repositories\interfaces
 */
interface AlgorithmRepositoryInterface
{
    public function query(array $select = []): AlgorithmQuery;
    public function removeByWebsite(Website $website): bool;
}