<?php

namespace core\helpers;

use Exception;
use thamtech\uuid\helpers\UuidHelper;

/**
 * Class ParseHelper
 * @package core\helpers
 */
class ParseHelper
{
    public static function generateXmlFileName(int $bytes = 32): string
    {
        try {
            $basename = substr(bin2hex(random_bytes($bytes)), 0, 20);
        } catch (Exception $e) {
            $basename = UuidHelper::uuid();
        }

        return $basename . '.xml';
    }
}