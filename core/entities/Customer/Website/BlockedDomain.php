<?php

namespace core\entities\Customer\Website;

use core\queries\BlockedDomainQuery;
use kak\clickhouse\ActiveRecord;

/**
 * Class BlockedDomain
 * @package core\entities\Customer\Website
 *
 * @property int $blocker_id
 * @property int $blocked_id
 *
 * @property Website $blocked
 */
class BlockedDomain extends ActiveRecord
{
    public static function tableName(): string
    {
        return 'website_blocked_domains';
    }

    public static function create(int $blockerId, int $blockedId): self
    {
        $blockedDomain = new self();
        $blockedDomain->blocker_id = $blockerId;
        $blockedDomain->blocked_id = $blockedId;
        return $blockedDomain;
    }

    public static function find(): BlockedDomainQuery
    {
        return new BlockedDomainQuery(self::class);
    }

    public function remove(array $condition) {
        return self::find()->deleteRecord('website_blocked_domains', $condition);
    }
}