<?php

namespace core\repositories;

use core\entities\Customer\Website\PostRemovalReason;

class PostRemovalReasonRepository extends AbstractRepository
{
    public function getBy(array $condition)
    {
        return PostRemovalReason::find()->where($condition)->limit(1)->one();
    }
}