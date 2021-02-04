<?php

namespace core\helpers;

/**
 * Class RequestHelper
 * @package core\helpers
 */
class RequestHelper
{
    public static function isPageRefreshed()
    {
        return isset($_SERVER['HTTP_CACHE_CONTROL']);
    }
}