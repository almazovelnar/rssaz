<?php

namespace core\repositories;

use RuntimeException;
use yii\db\Expression;
use core\queries\SessionQuery;
use core\entities\Session\Session;
use core\repositories\interfaces\SessionRepositoryInterface;

/**
 * Class SessionRepository
 * @package core\repositories
 */
class SessionRepository implements SessionRepositoryInterface
{
    public function get(string $id)
    {
        return $this->query(['s.id', 's.website_id', 's.algorithm', 'toString(s.posts) as posts'])
            ->filter(['id' => $id])
            ->firstOrFail();
    }

    public function query(array $select = []): SessionQuery
    {
        return Session::find()
            ->select($select)
            ->from('sessions s');
    }

    public function getClickCountForIP(string $ip, int $postId)
    {
        return $this->query(["count(sc.post_id)"])
            ->innerJoin('session_clicks sc', 's.id = sc.session_id')
            ->filter(['ip' => $ip])
            ->andWhere(new Expression("toDate(sc.created_at) = today() AND sc.post_id = :post", [':post' => $postId]))
            ->createCommand()
            ->queryScalar();
    }

    public function postExistsInSession(int $postId, string $sessionId)
    {
        return Session::find()
            ->select("id, website_id, algorithm")
            ->where('has(posts, :postId) AND id = :uuid', [':postId' => $postId, ':uuid' => $sessionId])
            ->first();
    }

    public function updatePosts(Session $session, array $newPosts)
    {
        $updatedPosts = json_encode(array_merge($session->getPosts(), $newPosts));
        return Session::getDb()
            ->createCommand("ALTER TABLE sessions UPDATE posts = {$updatedPosts} WHERE id = :id")
            ->bindValue(':id', $session->getId())
            ->execute()
            ->getIsOk();
    }

    public function save(Session $session): Session
    {
        if (!$session->insert())
            throw new RuntimeException("Can't save record.");
        return $session;
    }
}