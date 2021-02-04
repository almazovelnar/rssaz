<?php

namespace core\repositories;

use core\queries\WhiteListedDomainQuery;
use core\entities\Customer\Website\{Website, WhiteListedDomain};
use core\repositories\interfaces\WhiteListedDomainRepositoryInterface;

/**
 * Class WhiteListedDomainRepository
 * @package core\repositories
 */
class WhiteListedDomainRepository implements WhiteListedDomainRepositoryInterface
{
    public function query(array $select = []): WhiteListedDomainQuery
    {
        return WhiteListedDomain::find()
            ->from("website_whitelisted_domains wwd")
            ->select($select);
    }

    public function all(array $filters = []): array
    {
        return $this->query()->filter($filters)->get();
    }

    public function removeByWebsite(Website $website): bool
    {
        return WhiteListedDomain::find()->deleteRecord(WhiteListedDomain::tableName(), ['website_id' => $website->getId()]);
    }

    public function getByWebsite(int $websiteId): array
    {
        return $this->all(['website' => $websiteId]);
    }
}
