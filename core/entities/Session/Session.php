<?php

namespace core\entities\Session;

use core\queries\SessionQuery;
use kak\clickhouse\ActiveRecord;
use thamtech\uuid\helpers\UuidHelper;

/**
 * Class Session
 * @package core\entities\Session
 *
 * @property string $id
 * @property int $website_id
 * @property string $ip
 * @property string $agent
 * @property string $algorithm
 * @property string|array $posts
 * @property string $created_at
 */
class Session extends ActiveRecord
{
    public static function tableName(): string
    {
        return 'sessions';
    }

    public static function find(): SessionQuery
    {
        return new SessionQuery(self::class);
    }

    public static function create(
        int $websiteId,
        string $ip,
        ?string $agent,
        ?string $algorithm
    ): self
    {
        $session = new self();
        $session->id = UuidHelper::uuid();
        $session->website_id = $websiteId;
        $session->ip = $ip;
        $session->agent = $agent ?? 'Bot';
        $session->algorithm = $algorithm;

        return $session;
    }

    public function assignPosts(array $posts): self
    {
        $this->posts = json_encode($posts);
        return $this;
    }

    public function getPosts(): array
    {
        return json_decode($this->posts, true);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getClicks()
    {
        return $this->hasMany(Click::class, ['session_id' => 'id']);
    }
}
