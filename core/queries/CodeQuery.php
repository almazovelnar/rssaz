<?php

namespace core\queries;

/**
 * Class Query
 * @package core\queries\code
 */
class CodeQuery extends AbstractQuery
{
    public function filterByWebsite(AbstractQuery $query, int $websiteId)
    {
        return $query->andWhere(['wc.website_id' => $websiteId]);
    }
}