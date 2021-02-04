<?php

namespace core\entities\Customer\Website;

use core\entities\User;
use yii\db\ActiveRecord;

/**
 * Class PostRemovalReason
 * @package core\entities\Customer\Website
 *
 * @property int $post_id
 * @property int $user_id
 * @property string $reason
 *
 */
class PostRemovalReason extends ActiveRecord
{
    public static function tableName(): string
    {
        return 'posts_removal_reasons';
    }

    /**
     * @param int $post_id
     * @param int $user_id
     * @param string $reason
     * @return PostRemovalReason
     */
    public static function create(int $post_id, int $user_id, string $reason): self
    {
        $postRemovalReason = new self;
        $postRemovalReason->post_id = $post_id;
        $postRemovalReason->user_id = $user_id;
        $postRemovalReason->reason = $reason;
        return $postRemovalReason;
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
