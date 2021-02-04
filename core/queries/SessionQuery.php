<?php

namespace core\queries;

/**
 * Class SessionQuery
 * @package core\queries
 */
class SessionQuery extends AbstractQuery
{
    public function filterById(SessionQuery $query, string $uuid)
    {
        return $query->andWhere(['s.id' => $uuid]);
    }

    public function filterByIp(SessionQuery $query, string $ip)
    {
        return $query->andWhere(['s.ip' => $ip]);
    }
}