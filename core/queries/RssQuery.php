<?php

namespace core\queries;

/**
 * Class RssQuery
 * @package core\queries
 */
class RssQuery extends AbstractQuery
{
    public function getLastInsertedId(): int
    {
        return $this
            ->select(['max(id) as maxId'])
            ->from('website_rss')
            ->limit(1)
            ->createCommand()
            ->queryScalar();
    }

    public function getPostsCount($id): int
    {
        return $this
            ->select(['count(id) as count'])
            ->from('website_posts')
            ->where(['rss_id' => $id])
            ->limit(1)
            ->createCommand()
            ->queryScalar();
    }

    public function filterById(RssQuery $query, int $id)
    {
        return $query->andWhere(['wr.id' => $id]);
    }

    public function filterByWebsite(RssQuery $query, int $websiteId)
    {
        return $query->andWhere(['wr.website_id' => $websiteId]);
    }

    public function filterByLang(RssQuery $query, ?string $lang)
    {
        return $query->andWhere(['wr.lang' => $lang]);
    }

    public function filterByIndexing(RssQuery $query, string $column)
    {
        return $query->indexBy($column);
    }
}