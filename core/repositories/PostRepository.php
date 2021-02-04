<?php

namespace core\repositories;

use yii\db\Expression;
use core\queries\PostQuery;
use kak\clickhouse\ActiveRecord;
use yii\base\InvalidConfigException;
use core\exceptions\NotFoundException;
use core\entities\Customer\Website\{Website, Post};
use core\repositories\interfaces\PostRepositoryInterface;

/**
 * Class PostRepository
 * @package core\repositories
 */
class PostRepository implements PostRepositoryInterface
{
    /**
     * @param int $id
     * @param array $filters
     * @return ActiveRecord
     * @throws InvalidConfigException
     * @throws NotFoundException
     */
    public function get(int $id, array $filters = [])
    {
        return $this->query()
            ->with(['website'])
            ->filter(array_merge(['id' => $id], $filters))
            ->firstOrFail();
    }

    public function all(int $limit, array $filters = [], $ordering = [])
    {
        return $this->query()
            ->with(['website'])
            ->limit($limit)
            ->filter(array_merge($filters, ['wp.status' => Post::STATUS_ACTIVE]))
            ->orderBy($ordering ?: ['wp.created_at' => SORT_DESC])
            ->get();
    }

    public function getPosts(array $select = [], array $filters = [])
    {
        return $this->query($select)
            ->with(['postImage'])
            ->filter($filters)
            ->get();
    }

    public function getLastInsertedId(): int
    {
        return $this->query(['max(id) as maxId'])->limit(1)->createCommand()->queryScalar();
    }

    public function getByIds(array $ids, array $select = [])
    {
        return $this->query($select)->filter(['ids' => $ids, 'status' => Post::STATUS_ACTIVE])->get();
    }

    public function getByImage(string $filename)
    {
        return $this->query(['wp.id', 'wp.title'])
            ->andWhere(new Expression("wp.created_at >= (minus(now(), toIntervalDay(1)))"))
            ->filter(['image' => $filename])
            ->get();
    }

    public function getByTitle(string $title, array $filters = [])
    {
        return $this->query(['wp.id'])
            ->filter(array_merge($filters, ['title' => $title]))
            ->first();
    }

    public function getNew(int $limit, array $filters = [])
    {
        return $this->query(["wp.id"])
            ->byNewPostsAlgo()
            ->withinDays(1)
            ->limit($limit)
            ->filter(array_merge(['status' => Post::STATUS_ACTIVE], $filters))
            ->asArray()
            ->get();
    }

    public function getPopular(int $limit, array $filters = []): PostQuery
    {
        return $this->query(["wp.id"])
            ->byPopularAlgo()
            ->limit($limit)
            ->filter(array_merge(['status' => Post::STATUS_ACTIVE], $filters))
            ->asArray();
    }

    public function getPrioritized(int $limit, array $filters = [])
    {
        return $this->query(["wp.id"])
            ->byPrioritizedAlgo()
            ->withinPeriod()
            ->limit($limit)
            ->filter(array_merge(['status' => Post::STATUS_ACTIVE], $filters))
            ->asArray()
            ->get();
    }

    /**
     * @param string $guid
     * @return Post|ActiveRecord|null
     * @throws InvalidConfigException
     */
    public function getByGuid(string $guid): ?Post
    {
        return $this->query()->with(['website'])->filter(['guid' => $guid])->first();
    }

    public function increment(string $column, $posts): bool
    {
        $condition = '';
        if (is_array($posts))
            $condition = 'IN (' . implode(',', $posts) . ')';

        if ($posts instanceof Post)
            $condition = '= ' . $posts->getId();

        $response = Post::getDb()
            ->createCommand("ALTER TABLE website_posts UPDATE {$column} = {$column} + 1 WHERE id {$condition}")
            ->execute();

        return $response->getIsOk();
    }

    public function calculateCtr($posts): bool
    {
        $condition = '';
        if (is_array($posts))
            $condition = 'IN (' . implode(',', $posts) . ')';

        if ($posts instanceof Post)
            $condition = '= ' . $posts->getId();

        $response = Post::getDb()
            ->createCommand("
                 ALTER TABLE website_posts UPDATE ctr = if(views > 0, round(divide(clicks, views) * 100, 1), 0)
                 WHERE id {$condition}
            ")
            ->execute();

        return $response->getIsOk();
    }

    public function changePriority(Post $post, int $priority)
    {
        return $this->update($post->id, ['priority' => $priority]);
    }

    public function query(array $select = []): PostQuery
    {
        return Post::find()
            ->select($select)
            ->from("website_posts wp");
    }

    public function save(Post $post): Post
    {
        return $post;
    }

    public function update(int $postId, array $fields): bool
    {
        return Post::find()->updateRecord('website_posts', $fields, ['id' => $postId]);
    }

    public function remove(Post $post): bool
    {
        return Post::find()->deleteRecord('website_posts', ['id' => (int) $post->id]);
    }

    public function removeByWebsite(Website $website): bool
    {
        return Post::find()->deleteRecord('website_posts', ['website_id' => $website->id]);
    }

    public function changeStatus(Post $post, string $status)
    {
        return $this->update($post->id, ['status' => $status]);
    }

    public function getReservedPosts(array $filters = [])
    {
        return $this->query(["wp.id"])
            ->withinDays(2)
            ->orderBy("wp.ctr DESC")
            ->limit(100)
            ->filter(array_merge(['status' => Post::STATUS_ACTIVE], $filters))
            ->asArray()
            ->get();
    }

    public function getPostsCountByImage(array $filters)
    {
        return $this->query(['count(wp.id)'])
            ->filter($filters)
            ->createCommand()
            ->queryScalar();
    }

    public function getBySimilarTitle(string $title, array $filters = [])
    {
        return $this->query(['wp.id'])
            ->bySimilarTitle($title)
            ->filter($filters)
            ->first();
    }
}