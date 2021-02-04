<?php

namespace core\repositories\interfaces;

use core\queries\WhiteListedDomainQuery;
use core\entities\Customer\Website\Website;

/**
 * Interface WhiteListedDomainRepositoryInterface
 * @package core\repositories\interfaces
 */
interface WhiteListedDomainRepositoryInterface
{
    public function all(array $filters = []): array;
    public function query(array $select = []): WhiteListedDomainQuery;
    public function getByWebsite(int $websiteId): array;
    public function removeByWebsite(Website $website): bool;
}