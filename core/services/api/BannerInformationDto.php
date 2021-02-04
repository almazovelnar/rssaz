<?php

namespace core\services\api;

/**
 * Class BannerInformationDto
 * @package core\services\api
 */
class BannerInformationDto
{
    private static array $information = [];

    public static function setInformation(array $information)
    {
        self::$information = $information;
    }

    public static function getInformation(): array
    {
        return self::$information;
    }
}