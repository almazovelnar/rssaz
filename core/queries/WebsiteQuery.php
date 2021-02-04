<?php

namespace core\queries;

/**
 * Class Query
 * @package core\queries\website
 */
class WebsiteQuery extends AbstractQuery
{
    public function getLastInsertedId(): int
    {
        return $this
            ->select('max(id) as maxId')
            ->from('websites')
            ->limit(1)
            ->createCommand()
            ->queryScalar();
    }

    public function filterById(WebsiteQuery $query, int $id)
    {
        return $query->andWhere(['w.id' => $id]);
    }

    public function filterByName(WebsiteQuery $query, string $name)
    {
        return $query->andWhere(['w.name' => $name]);
    }

    public function filterByAddress(WebsiteQuery $query, string $address)
    {
        return $query->andWhere(['like', 'w.address', $address]);
    }

    public function filterByCustomer(WebsiteQuery $query, int $id)
    {
        return $query->andWhere(['w.customer_id' => $id]);
    }

    public function filterByExclude(WebsiteQuery $query, int $id)
    {
        return $query->andWhere(['<>', 'w.id', $id]);
    }

    public function filterByHash(WebsiteQuery $query, string $hash)
    {
        return $query->andWhere(['w.hash' => $hash]);
    }

    public function filterByStatus(WebsiteQuery $query, string $status)
    {
        return $query->andWhere(['w.status' => $status]);
    }

    public function filterByIndexing(WebsiteQuery $query, string $column)
    {
        return $query->indexBy($column);
    }
}