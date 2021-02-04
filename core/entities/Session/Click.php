<?php

namespace core\entities\Session;

use RuntimeException;
use kak\clickhouse\ActiveRecord;

/**
 * Class Click
 * @package core\entities\Session
 *
 * @property string session_id
 * @property int $post_id
 */
class Click extends ActiveRecord
{
    public static function tableName(): string
    {
        return 'session_clicks';
    }

    public static function create(string $sessionId, int $postId): self
    {
        $click = new self();
        $click->session_id = $sessionId;
        $click->post_id = $postId;
        if (!$click->insert())
            throw new RuntimeException("Can't save session click !");
        return $click;
    }

    public function getSession()
    {
        return $this->hasOne(Session::class, ['id' => 'session_id']);
    }
}