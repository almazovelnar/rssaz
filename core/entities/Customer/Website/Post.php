<?php

namespace core\entities\Customer\Website;

use core\queries\PostQuery;
use kak\clickhouse\ActiveRecord;
use core\entities\{Category\Category, Customer\Customer, Image};

/**
 * Class Post
 * @package core\entities\Customer\Website
 *
 * @property int $id
 * @property int $website_id
 * @property int $rss_id
 * @property int $category_id
 * @property string $lang
 * @property string $image
 * @property string $guid
 * @property string $title
 * @property string $link
 * @property string $description
 * @property int $views
 * @property int $clicks
 * @property double $ctr
 * @property string $color
 * @property int $priority
 * @property string $created_at
 * @property string $parsed_at
 * @property string $status
 *
 * @property Customer $customer
 * @property Website $website
 * @property Category $category
 */
class Post extends ActiveRecord
{
    public const PRIORITY_DEFAULT = 0;
    public const PRIORITY_CUSTOMER = 1;
    public const PRIORITY_ADMIN = 10;

    public const REDIRECT_SIMILAR_POSTS_DAYS = 10;

    public const STATUS_ACTIVE = 'active';
    public const STATUS_MODERATE = 'moderate';
    public const STATUS_BLOCKED = 'blocked';
    public const STATUS_DUPLICATED = 'duplicated';

    private array $priorityPostTypes = ['backgrounded-news', 'highlighted', 'floated-news'];

    public static function tableName(): string
    {
        return 'website_posts';
    }

    public static function primaryKey(): array
    {
        return ["id", "created_at"];
    }

    public static function find()
    {
        return new PostQuery(self::class);
    }

    public function edit(
        int $category_id,
        string $title,
        string $link,
        string $description,
        string $image,
        string $color
    ): void
    {
        $this->category_id = $category_id;
        $this->title = $title;
        $this->link = $link;
        $this->description = $description;
        $this->image = $image;
        $this->color = $color;
    }

    public function editStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setCTR($ctr): void
    {
        $this->ctr = $ctr;
    }

    public function getPriority(): int
    {
        return $this->priority;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function getLink(): string
    {
        return $this->link;
    }

    public function prioritizedByCustomer(): bool
    {
        return $this->priority < 10;
    }

    public function prioritized(): bool
    {
        return $this->priority > 0;
    }

    public function isDuplicated(): bool
    {
        return $this->status == Post::STATUS_DUPLICATED;
    }

    public function getWebsite()
    {
        return $this->hasOne(Website::class, ['id' => 'website_id']);
    }

    public function getPostImage()
    {
        return $this->hasOne(Image::class, ['filename' => 'image']);
    }

    public function getPriorityPostTypes(): array
    {
        return $this->priorityPostTypes;
    }
}
