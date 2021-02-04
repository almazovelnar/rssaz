<?php

namespace core\helpers;

use core\entities\Customer\Website\Code;

/**
 * Class CodeHelper
 * @package core\helpers
 */
class CodeHelper
{
    /**
     * @return array
     */
    public static function getFonts(): array
    {
        return [
            Code::FONT_ARIAL    => Code::FONT_ARIAL,
            Code::FONT_CONSOLAS => Code::FONT_CONSOLAS,
            Code::FONT_VERDANA  => Code::FONT_VERDANA,
        ];
    }

    /**
     * @return array
     */
    public static function getFontStyles(): array
    {
        return [
            Code::STYLE_NORMAL => 'Normal',
            Code::STYLE_LIGHT  => 'Light',
            Code::STYLE_BOLD   => 'Bold',
        ];
    }

    /**
     * @return array
     */
    public static function getDirections(): array
    {
        return [
            Code::DIRECTION_HORIZONTAL => 'Horizontal (Üfüqi)',
            Code::DIRECTION_VERTICAL  => 'Vertical (Şaquli)',
        ];
    }

    /**
     * @param string|null $color
     * @return array
     */
    public static function parseColor(?string $color): array
    {
        if (!$color) return [];

        $color = json_decode($color, true);
        return (count($color) !== 3) ? [] : array_values($color);
    }
}
