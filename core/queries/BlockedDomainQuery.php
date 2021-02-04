<?php

namespace core\queries;

/**
 * Class BlockedDomainQuery
 * @package core\queries
 */
class BlockedDomainQuery extends AbstractQuery
{
    public function filterByWebsite(BlockedDomainQuery $query, int $websiteId)
    {
        return $query->andWhere(['wbd.blocker_id' => $websiteId]);
    }
}