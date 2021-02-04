<?php

namespace core\entities\Customer\Website;

use Yii;
use Exception;
use core\queries\WebsiteQuery;
use kak\clickhouse\ActiveRecord;
use core\entities\Customer\Customer;

/**
 * Class Website
 *
 * @package core\entities\Customer\Website
 *
 * @property int $id
 * @property int $customer_id
 * @property string $name
 * @property int $traffic_limit
 * @property string $address
 * @property string $status
 * @property string $default_lang
 * @property string $hash
 * @property float $rate_min
 * @property float $rate_actual
 * @property int $period
 * @property int $update_frequency
 * @property string $created_at
 *
 * @property Code $code
 * @property BlockedDomain[] $blockedDomains
 * @property WhiteListedDomain[] $whiteListedDomains
 * @property Algorithm[]|array $algorithms
 * @property Rss[] $rss
 */
class Website extends ActiveRecord
{
    const STATUS_ACTIVE = 'active';
    const STATUS_WAITING = 'waiting';
    const STATUS_BLOCKED = 'blocked';

    public static function tableName(): string
    {
        return 'websites';
    }

    public static function primaryKey(): array
    {
        return ["id"];
    }

    public static function find(): WebsiteQuery
    {
        return new WebsiteQuery(self::class);
    }

    public static function create(
        int $customerId,
        string $name,
        int $trafficLimit,
        string $address,
        string $status,
        string $defaultLang,
        float $rateMin,
        int $updateFrequency = 10
    ): self
    {
        $website = new self();
        $website->id = (self::find()->getLastInsertedId() + 1);
        $website->customer_id = $customerId;
        $website->name = $name;
        $website->traffic_limit = $trafficLimit;
        $website->address = $address;
        $website->status = $status;
        $website->default_lang = $defaultLang;
        $website->update_frequency = $updateFrequency;
        $website->rate_min = $rateMin;
        $website->generateHash();
        return $website;
    }

    public function edit(
        int $customerId,
        string $name,
        int $trafficLimit,
        int $updateFrequency,
        string $defaultLang,
        string $address,
        string $status,
        float $rateMin
    ): void
    {
        $this->customer_id = $customerId;
        $this->name = $name;
        $this->traffic_limit = $trafficLimit;
        $this->update_frequency = $updateFrequency;
        $this->default_lang = $defaultLang;
        $this->address = $address;
        $this->status = $status;
        $this->rate_min = $rateMin;
    }

    public function setOrCreateRss(string $language, string $rssAddress)
    {
        foreach ($this->rss as $rssObj) {
            if ($rssObj->exists($language)) {
                $rssObj->edit($rssAddress);
                return;
            }
        }

        $this->createRss($language, $rssAddress)->insert();
    }

    public function unsetRss(string $language)
    {
        return Rss::remove($this->id, $language);
    }

    /**
     * @param string $language
     * @param string $rssAddress
     * @return Rss
     */
    public function createRss(string $language, string $rssAddress): Rss
    {
        return Rss::create($this->id, $language, $rssAddress);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function getHash(): string
    {
        return $this->hash;
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function isBlocked(): bool
    {
        return $this->status === self::STATUS_BLOCKED;
    }

    public function isItself()
    {
        return $this->address === Yii::$app->params['frontendHostInfo'];
    }

    /**
     * @throws Exception
     */
    public function generateHash(): void
    {
        $this->hash = substr(bin2hex(random_bytes(16)), 0, 10);
    }

    public function getDefaultLanguage(): ?string
    {
        return $this->default_lang;
    }

    public function prepareExcludedDomains(array $blockedDomains): array
    {
        return array_merge([$this->getId()], array_column($blockedDomains, 'blocked_id'));
    }

    public function getCode()
    {
        return $this->hasOne(Code::class, ['website_id' => 'id']);
    }

    public function getRssByLanguage(string $code)
    {
        return $this->hasOne(Rss::class, ['website_id' => 'id'])->andWhere(['lang' => $code]);
    }

    public function getRss()
    {
        return $this->hasMany(Rss::class, ['website_id' => 'id']);
    }

    public function getBlockedDomains()
    {
        return $this->hasMany(BlockedDomain::class, ['blocker_id' => 'id']);
    }

    public function getWhiteListedDomains()
    {
        return $this->hasMany(WhiteListedDomain::class, ['website_id' => 'id']);
    }

    public function getAlgorithms()
    {
        return $this->hasMany(Algorithm::class, ['website_id' => 'id']);
    }

    public function getCustomer()
    {
        return $this->hasOne(Customer::class, ['id' => 'customer_id']);
    }
}
