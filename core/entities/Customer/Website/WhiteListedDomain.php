<?php

namespace core\entities\Customer\Website;

use core\queries\WhiteListedDomainQuery;
use kak\clickhouse\ActiveRecord;

/**
 * Class WhiteListedDomain
 * @package core\entities\Customer\Website
 *
 * @property int $website_id
 * @property int $whitelisted_id
 */
class WhiteListedDomain extends ActiveRecord
{
    public static function tableName(): string
    {
        return 'website_whitelisted_domains';
    }

    public static function create(int $websiteId, int $whitelistedId): self
    {
        $whitelistedDomain = new self();
        $whitelistedDomain->website_id = $websiteId;
        $whitelistedDomain->whitelisted_id = $whitelistedId;
        return $whitelistedDomain;
    }

    public static function find(): WhiteListedDomainQuery
    {
        return new WhiteListedDomainQuery(self::class);
    }

    public function remove(array $condition)
    {
        return self::find()->deleteRecord(self::tableName(), $condition);
    }
}