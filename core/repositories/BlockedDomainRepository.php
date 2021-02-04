<?php

namespace core\repositories;

use core\queries\BlockedDomainQuery;
use core\entities\Customer\Website\{Website, BlockedDomain};
use core\repositories\interfaces\BlockedDomainRepositoryInterface;

/**
 * Class BlockedDomainRepository
 * @package core\repositories
 */
class BlockedDomainRepository implements BlockedDomainRepositoryInterface
{
    public function query(array $select = []): BlockedDomainQuery
    {
        return BlockedDomain::find()
            ->from("website_blocked_domains wbd")
            ->select($select);
    }

    public function all(array $filters = []): array
    {
        return $this->query()
            ->filter($filters)
            ->get();
    }

    public function removeByWebsite(Website $website): bool
    {
        return BlockedDomain::find()->deleteRecord('website_blocked_domains', ['blocker_id' => $website->id]);
    }

    /**
     * @param int $websiteId
     * @return array
     */
    public function getByWebsite(int $websiteId): array
    {
        return $this->query()->filter(['website' => $websiteId])->get();
    }
}
