<?php

namespace core\queries;

/**
 * Class WhiteListedDomainQuery
 * @package core\queries
 */
class WhiteListedDomainQuery extends AbstractQuery
{
    public function filterByWebsite(WhiteListedDomainQuery $query, int $websiteId)
    {
        return $query->andWhere(['wwd.website_id' => $websiteId]);
    }
}