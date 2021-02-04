<?php

namespace core\repositories;

use RuntimeException;
use core\queries\RssQuery;
use core\entities\Customer\Website\{Website, Rss};
use core\repositories\interfaces\RssRepositoryInterface;

/**
 * Class RssRepository
 * @package core\repositories
 */
class RssRepository implements RssRepositoryInterface
{
    public function get(int $id)
    {
        return $this->query()
            ->filter(['id' => $id])
            ->firstOrFail();
    }

    public function query(array $select = []): RssQuery
    {
        return Rss::find()
            ->from('website_rss wr')
            ->select($select);
    }

    public function all(array $params = []): array
    {
        return $this->query(['wr.*', 'w.customer_id as customer'])
            ->innerJoin('websites w', 'w.id = wr.website_id')
            ->where(['w.status' => Website::STATUS_ACTIVE, 'valid' => 1])
            ->filter($params)
            ->get();
    }

    public function getByWebsite(int $websiteId, string $language)
    {
        return $this->query()->filter(['website' => $websiteId, 'lang' => $language])->firstOrFail();
    }

    public function allByWebsite(int $websiteId)
    {
        return $this->all(['website' => $websiteId]);
    }

    public function getPostsCount(int $id)
    {
        return $this->query()->getPostsCount($id);
    }

    public function save(Rss $rss): Rss
    {
        if (!$rss->insert())
            throw new RuntimeException("Can't save rss !");
        return $rss;
    }

    public function remove(Rss $rss): bool
    {
        return Rss::find()->deleteRecord('website_rss', ['id' => $rss->id]);
    }

    public function removeByWebsite(Website $website): bool
    {
        return Rss::find()->deleteRecord('website_rss', ['website_id' => $website->id]);
    }
}
