<?php

namespace core\entities\Customer\Website;

use core\queries\RssQuery;
use kak\clickhouse\ActiveRecord;

/**
 * Class Rss
 * @package core\entities\Customer\Website
 *
 * @property int $id
 * @property int $website_id
 * @property string $lang
 * @property string $rss_address
 * @property bool $valid
 * @property int $error_count
 * @property string $created_at
 *
 * @property Website $website
 * @property Post[] $posts
 */
class Rss extends ActiveRecord
{
    public const MAX_ERROR_COUNT = 3;

    public ?int $customer = null;

    public static function tableName(): string
    {
        return 'website_rss';
    }

    public static function primaryKey(): array
    {
        return ['id', 'created_at'];
    }

    public static function find(): RssQuery
    {
        return new RssQuery(self::class);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getFeedAddress(): string
    {
        return $this->rss_address;
    }

    public function getFeedLanguage(): string
    {
        return $this->lang;
    }

    public function getCustomer(): ?int
    {
        return $this->customer;
    }

    public function getWebsiteId(): int
    {
        return $this->website_id;
    }

    public static function create(int $websiteId, string $language, string $rssAddress): self
    {
        $rss = new self();
        $rss->id = (self::find()->getLastInsertedId() + 1);
        $rss->website_id = $websiteId;
        $rss->lang = $language;
        $rss->rss_address = $rssAddress;
        return $rss;
    }

    public function edit(string $rssAddress): bool
    {
        return self::find()->updateRecord(self::tableName(), ['rss_address' => $rssAddress], ['id' => $this->id]);
    }

    public function remove(int $websiteId, string $language)
    {
        return self::find()->deleteRecord('website_rss', ['website_id' => $websiteId, 'lang' => $language]);
    }

    public function getWebsite()
    {
        return $this->hasOne(Website::class, ['id' => 'website_id']);
    }

    public function incrementError()
    {
        $this->error_count++;
        if ($this->error_count >= self::MAX_ERROR_COUNT) {
            $this->markAsInvalid();
            $this->error_count = 0;
        }
    }

    public function exists(string $language): bool
    {
        return $this->lang === $language;
    }

    public function markAsInvalid(): void
    {
        $this->valid = false;
    }

    public function isValid(): bool
    {
        return $this->valid == true;
    }

    public function getPosts()
    {
        return $this->hasMany(Post::class, ['rss_id' => 'id']);
    }
}
