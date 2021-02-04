<?php

namespace core\queries;

use yii\db\Expression;
use core\valueObjects\ComputedImageHash;

/**
 * Class ImageQuery
 *
 * @method ImageQuery forToday()
 */
class ImageQuery extends AbstractQuery
{
    public function filterByHash(ImageQuery $query, string $hash): ImageQuery
    {
        return $query->andWhere(['i.hash' => $hash]);
    }

    public function filterByChunk(ImageQuery $query, ComputedImageHash $computedHash): ImageQuery
    {
        return $query->andWhere(new Expression(
            '(substring(i.hash, 1, 4) = :firstChunk) OR (substring(i.hash, length(i.hash) - 4) = :lastChunk)', [
                ':firstChunk' => $computedHash->getFirstChunk(),
                ':lastChunk'  => $computedHash->getLastChunk(),
            ]
        ));
    }

    public function scopeForToday(ImageQuery $query): ImageQuery
    {
        return $query->andWhere(new Expression('i.created_at >= (minus(now(), toIntervalDay(1)))'));
    }

    public function filterByFilename(ImageQuery $query, string $filename): ImageQuery
    {
        return $query->andWhere(['i.filename' => $filename]);
    }

    public function filterByFilenames(ImageQuery $query, array $filenames): ImageQuery
    {

        return $query->andWhere(new Expression("i.filename IN (" . implode(',', $filenames) . ")"));
    }

    public function filterByLastHour(ImageQuery $query, int $hour): ImageQuery
    {
        return $query->andWhere(new Expression("i.created_at >= (now() - INTERVAL {$hour} HOUR)"));
    }
}