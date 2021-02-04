<?php

namespace core\services\statistics;

/**
 * Class TrafficCalculator
 * @package core\services\statistics
 */
class TrafficCalculator
{
    public function calculateCtr(TrafficDto $trafficDto)
    {
        return ($trafficDto->hasAnyViews())
            ? round(($trafficDto->getClicks() / $trafficDto->getViews()) * 100, 1)
            : 0;
    }
}