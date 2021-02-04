<?php

namespace core\repositories\interfaces;

use core\queries\BlockedDomainQuery;
use core\entities\Customer\Website\Website;

/**
 * Interface BlockedDomainRepositoryInterface
 * @package core\repositories\interfaces
 */
interface BlockedDomainRepositoryInterface
{
    public function query(array $select = []): BlockedDomainQuery;
    public function getByWebsite(int $websiteId): array;
    public function removeByWebsite(Website $website): bool;
}