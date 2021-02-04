<?php

namespace core\entities\Customer\Website;

use core\queries\AlgorithmQuery;
use kak\clickhouse\ActiveRecord;

/**
 * Class Algorithm
 * @package core\entities\Customer\Website
 *
 * @property int $website_id
 * @property string algorithm
 */
class Algorithm extends ActiveRecord
{
    public const DEFAULT_ALGORITHM = 'default';

    public static function tableName(): string
    {
        return 'website_algorithms';
    }

    public static function create(int $websiteId, string $algorithmClass): self
    {
        $algorithm = new self();
        $algorithm->website_id = $websiteId;
        $algorithm->algorithm = $algorithmClass;
        return $algorithm;
    }

    public static function find(): AlgorithmQuery
    {
        return new AlgorithmQuery(self::class);
    }
}