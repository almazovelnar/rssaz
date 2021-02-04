<?php

namespace core\repositories\interfaces;

use core\queries\WebsiteQuery;
use core\exceptions\NotFoundException;
use core\entities\Customer\Website\Website;

/**
 * Interface WebsiteRepositoryInterface
 * @package core\repositories\interfaces
 */
interface WebsiteRepositoryInterface
{
    /**
     * @param int $id
     * @return mixed
     * @throws NotFoundException
     */
    public function get(int $id);
    public function query(array $select = []): WebsiteQuery;
    public function getAggregator(): Website;
    public function getByCustomer(int $customerId): array;
    /**
     * @param string $hash
     * @return Website
     * @throws NotFoundException
     */
    public function getByHash(string $hash): Website;
    public function save(Website $website): Website;
    public function remove(Website $website): bool;
}