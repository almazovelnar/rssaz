<?php

namespace core\entities\Customer\Website;

use yii\db\ActiveRecord;

/**
 * Class PostDuplicateReason
 * @package core\entities\Customer\Website
 *
 * @property int id
 * @property int original_post_id
 * @property int duplicated_post_id
 * @property string reason
 * @property float similarity
 * @property string created_at
 */
class PostDuplicateReason extends ActiveRecord
{
    public static function tableName(): string
    {
        return 'posts_duplicate_reasons';
    }

    public static function create(
        int $originalId,
        int $duplicatedId,
        string $reason,
        float $similarity
    ): self
    {
        $duplicateReason = new self();
        $duplicateReason->original_post_id = $originalId;
        $duplicateReason->duplicated_post_id = $duplicatedId;
        $duplicateReason->reason = $reason;
        $duplicateReason->similarity = $similarity;
        return $duplicateReason;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getOriginalPostId(): int
    {
        return $this->original_post_id;
    }

    public function getDuplicatedPostId(): int
    {
        return $this->duplicated_post_id;
    }
}