<?php

namespace frontend\repositories;

use Yii;
use yii\db\Expression;
use core\queries\PostQuery;
use core\entities\Customer\Website\Post;
use core\repositories\interfaces\PostRepositoryInterface;

/**
 * Class PostRepository
 * @package frontend\repositories
 */
class PostRepository implements PostRepositoryInterface
{
    private string $language;

    public function __construct()
    {
        $this->language = Yii::$app->language;
    }

    public function get(int $id, array $filters = [])
    {
        return $this->query()
            ->with(['website'])
            ->filter(array_merge(['id' => $id], $filters))
            ->firstOrFail();
    }

    public function getByIds(array $ids, array $select = [])
    {
        return $this->query($select)->filter(['ids' => $ids])->get();
    }

    public function query(array $select = []): PostQuery
    {
        return Post::find()
            ->from("website_posts wp")
            ->select($select)
            ->andWhere(['wp.lang' => $this->language])
            ->andWhere(['wp.status' => Post::STATUS_ACTIVE]);
    }

    public function all(
        int $limit,
        array $filters = [],
        array $ordering = []
    )
    {
        return $this
            ->query(['id', 'category_id', 'title', 'image', 'created_at', 'website_id', 'parsed_at'])
            ->with(['website'])
            ->limit($limit)
            ->filter($filters)
            ->orderBy($ordering ?: ['wp.created_at' => SORT_DESC])
            ->asArray()
            ->get();
    }

    public function getPostsCount(
        int $limit,
        array $filters = []
    )
    {
        return $this->query(['id'])
            ->with(['website'])
            ->limit($limit)
            ->filter($filters)
            ->asArray()
            ->count();
    }

    public function allInnerWebsite(
        int $limit,
        array $filters = [],
        array $ordering = []
    )
    {
        return $this
            ->query(['id', 'category_id', 'image', 'title', 'address', 'name', 'created_at', 'description', 'priority', 'website_id'])
            ->innerJoin('websites w', 'w.id = wp.website_id')
            ->limit($limit)
            ->filter($filters)
            ->orderBy($ordering ?: ['wp.created_at' => SORT_DESC])
            ->asArray()
            ->get();
    }

    public function getPopular(int $limit, array $params = []): array
    {
        return $this->all($limit, $params, ['wp.ctr' => SORT_DESC]);
    }

    public function getInnerPopular(int $limit, array $params = []): array
    {
        return $this->allInnerWebsite($limit, $params, ['wp.ctr' => SORT_DESC]);
    }

    public function getAllExcludeOne(int $id, int $limit): array
    {
        return $this->allInnerWebsite($limit, ['excludeOne' => $id, 'rate' => true]);
    }

    public function getByCategoryInDays(int $id, int $limit, int $days): array
    {
        return $this->allInnerWebsite(
            $limit,
            ['category' => $id, 'lastDays' => $days],
            ['wp.ctr' => SORT_DESC]
        );
    }

    public function getByCategoryExcludeOneInDays(int $categoryId, int $id, int $limit, int $days): array
    {
        return $this->allInnerWebsite(
            $limit,
            ['category' => $categoryId, 'excludeOne' => $id, 'lastDays' => $days, 'rate' => true],
            ['wp.ctr' => SORT_DESC]
        );
    }

    public function getPostsCountByCategoryExcludeOneInDays(int $categoryId, int $id, int $limit, int $days): int
    {
        return $this
            ->query()
            ->innerJoin('websites w', 'w.id = wp.website_id')
            ->limit($limit)
            ->filter(['category' => $categoryId, 'excludeOne' => $id, 'lastDays' => $days, 'rate' => true])
            ->count();
    }

    public function getRandomNextPostByCategory(int $categoryId, int $id)
    {
        return $this->query(['id'])
            ->filter(['category' => $categoryId, 'excludeOne' => $id, 'lastHours' => 3])
            ->orderBy(new Expression('rand()'))
            ->asArray()
            ->first();
    }
}
