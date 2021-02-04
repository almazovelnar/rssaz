<?php

namespace core\queries;

use yii\db\Expression;

/**
 * Class PostQuery
 * @package core\entities\queries
 *
 * @method PostQuery byNewPostsAlgo()
 * @method PostQuery byPopularAlgo()
 * @method PostQuery byPrioritizedAlgo()
 * @method PostQuery withinDays(int $days)
 * @method PostQuery withinPeriod()
 * @method PostQuery withinHours(int $hours)
 * @method PostQuery bySimilarTitle(string $string)
 */
class PostQuery extends AbstractQuery
{
    public function scopeByNewPostsAlgo(PostQuery $query)
    {
        return $query
            ->innerJoin("websites w", "w.id = wp.website_id")
            ->andWhere(new Expression("wp.views >= 0 AND wp.views <= 700"))
            ->andWhere(new Expression("wp.clicks <= 2"))
            ->andWhere(new Expression("wp.priority = 0"))
            ->orderBy('wp.created_at DESC');
    }

    public function scopeByPopularAlgo(PostQuery $query)
    {
        return $query
            ->innerJoin("websites w", "w.id = wp.website_id")
            ->andWhere(new Expression("wp.priority = 0"))
            // ->innerJoin("website_posts w2", "w2.id = wp.id AND w2.created_at BETWEEN (now() - INTERVAL w.period DAY) AND NOW()")
            ->orderBy("wp.ctr DESC");
    }

    public function scopeByPrioritizedAlgo(PostQuery $query)
    {
        return $query
            ->innerJoin("websites w", "w.id = wp.website_id")
            ->andWhere(new Expression("wp.priority > 0"))
            ->orderBy('wp.priority DESC');
    }

    public function scopeWithinPeriod(PostQuery $query, int $additionalPeriod = 0)
    {
        return $query->andWhere(new Expression("wp.created_at >= (minus(now(), toIntervalDay(w.period + {$additionalPeriod})))"));
    }

    public function scopeWithinDays(PostQuery $query, int $days)
    {
        return $query->andWhere(new Expression("wp.created_at >= (minus(now(), toIntervalDay(:days)))", [':days' => $days]));
    }

    public function scopeWithinHours(PostQuery $query, int $hours)
    {
        return $query->andWhere(new Expression("wp.created_at >= (minus(now(), toIntervalHour(:hours)))", [':hours' => $hours]));
    }

    public function scopeBySimilarTitle(PostQuery $query, string $title)
    {
        return $query->andWhere(["LIKE", "replaceAll(lowerUTF8(wp.title), 'ı', 'i')", "%{$title}%"]);
    }

    public function filterByLanguage(PostQuery $query, string $lang)
    {
        return $query->andWhere(['wp.lang' => $lang]);
    }

    public function filterById(PostQuery $query, int $id)
    {
        return $query->andWhere(['wp.id' => $id]);
    }

    public function filterByIds(PostQuery $query, array $ids)
    {
        return $query->andWhere(['IN', 'wp.id', $ids]);
    }

    public function filterByExcludedIds(PostQuery $query, array $ids)
    {
        return $query->andWhere(['NOT IN', 'wp.id', $ids]);
    }

    public function filterByWhiteListedDomains(PostQuery $query, array $domains)
    {
        return $query->andWhere(['IN', 'wp.website_id', $domains]);
    }

    public function filterByExcludedDomains(PostQuery $query, array $domains)
    {
        return $query->andWhere(['NOT IN', 'wp.website_id', $domains]);
    }

    public function filterByImage(PostQuery $query, string $image)
    {
        return $query->andWhere(['wp.image' => $image]);
    }

    public function filterByGuid(PostQuery $query, string $guid)
    {
        return $query->andWhere(['wp.guid' => trim($guid)]);
    }

    public function filterByPeriod(PostQuery $query, $period)
    {
        return $query->andWhere(new Expression("wp.created_at >= (now() - INTERVAL 1 {$period})"));
    }

    public function filterByLastHours(PostQuery $query, int $hours)
    {
        return $query->andWhere(new Expression("wp.created_at >= minus(now(), toIntervalHour(:hours))", [':hours' => $hours]));
    }

    public function filterByLastDays(PostQuery $query, int $days)
    {
        return $query->andWhere(new Expression("wp.created_at >= minus(now(), toIntervalDay(:days))", [':days' => $days]));
    }

    public function filterByLastMinutes(PostQuery $query, int $minutes)
    {
        return $query->andWhere(new Expression("wp.created_at >= minus(now(), toIntervalMinute(:minutes))", [':minutes' => $minutes]));
    }

    public function filterByToday(PostQuery $query)
    {
        return $query->andWhere(new Expression("toDate(wp.created_at) = today()"));
    }

    public function filterByPage(PostQuery $query, int $page)
    {
        return $query->offset(($page - 1) * $this->limit);
    }

    public function filterByTitle(PostQuery $query, string $title)
    {
        return $query->andWhere(["wp.title" => $title]);
    }

    public function filterByCategory(PostQuery $query, int $category)
    {
        return $query->andWhere(['wp.category_id' => $category]);
    }

    public function filterByExcludeOne(PostQuery $query, int $id)
    {
        return $query->andWhere(['!=', 'id', $id]);
    }

    public function filterByMinDate(PostQuery $query, string $date)
    {
        return $query->andWhere(['<', 'wp.created_at', $date]);
    }

    public function filterBySource(PostQuery $query, $websiteId)
    {
        return ($websiteId) ? $query->andWhere(['wp.website_id' => (int) $websiteId]) : $query;
    }

    public function filterByStatus(PostQuery $query, string $status)
    {
        return $query->andWhere(['wp.status' => $status]);
    }

    public function filterByRate(PostQuery $query)
    {
        return $query->andWhere(new Expression('w.rate_actual >= w.rate_min'));
    }

    public function filterByLastDate(PostQuery $query, string $date)
    {
        return $query->andWhere(['<=', 'wp.created_at', $date]);
    }

    public function filterByLastParsed(PostQuery $query, string $date)
    {
        return $query->andWhere(['<', 'wp.parsed_at', $date]);
    }
}
