<?php


namespace core\helpers;

/**
 * Class StringHelper
 * @package core\helpers
 */
class StringHelper
{
    public static function filter(string $string): string
    {
        return html_entity_decode(trim(str_replace(
            [html_entity_decode("&nbsp;"), "“", "”"],
            [' ', '"', '"'],
            $string
        )));
    }
}