<?php

namespace core\repositories;

use core\entities\Statistics\Statistics;

class StatisticsRepository extends AbstractRepository
{
    /**
     * @param array $condition
     * @return array|null|Statistics
     */
    protected function getBy(array $condition)
    {
        return Statistics::find()->where($condition)->limit(1)->one();
    }
}