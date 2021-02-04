<?php

namespace core\repositories;

use Yii;
use RuntimeException;
use core\queries\WebsiteQuery;
use kak\clickhouse\ActiveRecord;
use yii\base\InvalidConfigException;
use core\exceptions\NotFoundException;
use core\entities\Customer\Website\Website;
use kak\clickhouse\Connection as ClickHouseConnection;
use core\repositories\interfaces\WebsiteRepositoryInterface;

/**
 * Class WebsiteRepository
 * @package core\repositories
 */
class WebsiteRepository implements WebsiteRepositoryInterface
{
    private ClickHouseConnection $clickHouse;

    public function __construct()
    {
        $this->clickHouse = Yii::$app->clickhouse;
    }

    public function get(int $id)
    {
        return $this->query()
            ->with(['algorithms', 'code', 'blockedDomains', 'rss', 'whiteListedDomains'])
            ->filter(['id' => $id])
            ->firstOrFail();
    }

    public function query(array $select = []): WebsiteQuery
    {
        return Website::find()
            ->from('websites w')
            ->select($select);
    }

    /**
     * @param string $hash
     * @return Website|ActiveRecord
     * @throws InvalidConfigException
     * @throws NotFoundException
     */
    public function getByHash(string $hash): Website
    {
        return $this->query()
            ->with(['algorithms', 'code', 'blockedDomains'])
            ->filter(['hash' => $hash, 'status' => Website::STATUS_ACTIVE])
            ->firstOrFail();
    }

    public function getByCustomer(int $customerId): array
    {
        return $this->query()
            ->filter(['customer' => $customerId])
            ->orderBy(['w.name' => SORT_ASC])
            ->get();
    }

    public function getOneByCustomer(int $id, int $customerId)
    {
        return $this->query()
            ->with(['algorithms', 'code', 'blockedDomains', 'rss'])
            ->filter(['id' => $id, 'customer' => $customerId])
            ->firstOrFail();
    }

    public function getDomains(string $q, array $filters = [])
    {
        return $this->query(['w.id', 'w.name'])
            ->where(['like', 'w.name', htmlspecialchars($q)])
            ->filter($filters)
            ->get();
    }

    public function syncAlgorithms(Website $website, array $algorithms)
    {
        $this->clickHouse
            ->createCommand("ALTER TABLE website_algorithms DELETE WHERE website_id = :id", [':id' => $website->id])
            ->execute();

        if (empty($algorithms)) return;
        $data = [];
        foreach ($algorithms as $algorithm)
            $data[] = [$website->id, $algorithm];
        $this->clickHouse->createCommand()->batchInsert('website_algorithms', ['website_id', 'algorithm'], $data)->execute();
    }

    public function syncBlockedDomains(Website $website, array $blockedDomains)
    {
        $this->clickHouse
            ->createCommand("ALTER TABLE website_blocked_domains DELETE WHERE blocker_id = :id", [':id' => $website->getId()])
            ->execute();

        if (empty($blockedDomains)) return;

        $data = [];
        foreach ($blockedDomains as $blockedDomain)
            $data[] = [$website->id, (int)$blockedDomain];
        $this->clickHouse->createCommand()->batchInsert('website_blocked_domains', ['blocker_id', 'blocked_id'], $data)->execute();
    }

    public function syncWhiteListedDomains(Website $website, array $whiteListedDomains)
    {
        $this->clickHouse
            ->createCommand("ALTER TABLE website_whitelisted_domains DELETE WHERE website_id = :id", [':id' => $website->getId()])
            ->execute();

        if (empty($whiteListedDomains)) return;

        $data = [];
        foreach ($whiteListedDomains as $whiteListedDomain)
            $data[] = [$website->getId(), (int)$whiteListedDomain];
        $this->clickHouse->createCommand()->batchInsert('website_whitelisted_domains', ['website_id', 'whitelisted_id'], $data)->execute();
    }

    public function syncRss(Website $website, array $rss, bool $fullAddress = false)
    {
        foreach ($rss as $rssForm) {
            if (!$rssForm->isEmpty()) {
                $rssAddress = $fullAddress ? $rssForm->rssAddress : $website->address . $rssForm->rssAddress;
                $website->setOrCreateRss($rssForm->language, $rssAddress);
            } else {
                $this->clickHouse->createCommand("ALTER TABLE website_rss DELETE WHERE website_id = :website_id AND lang = :lang", [
                    ':website_id' => $website->id, ':lang' => $rssForm->language
                ])->execute();
            }
        }
    }

    /**
     * @return ActiveRecord|Website
     * @throws InvalidConfigException
     * @throws NotFoundException
     */
    public function getAggregator(): Website
    {
        return $this->query()->filter(['name' => 'rss.az'])->firstOrFail();
    }

    public function update(int $websiteId, array $fields): bool
    {
        return Website::find()->updateRecord('websites', $fields, ['id' => $websiteId]);
    }

    public function save(Website $website): Website
    {
        if (!$website->insert())
            throw new RuntimeException("Can't save website !");
        return $website;
    }

    public function remove(Website $website): bool
    {
        return Website::find()->deleteRecord('websites', ['id' => $website->id]);
    }

    public function all(array $params = [], array $ordering = [])
    {
        return $this->query()
            ->with(['algorithms'])
            ->filter($params)
            ->orderBy($ordering ?: ['w.name' => SORT_ASC])
            ->get();
    }

    public function changeStatus(int $websiteId, string $status)
    {
        return $this->update($websiteId, ['status' => $status]);
    }
}
