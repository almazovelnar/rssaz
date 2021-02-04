<?php

namespace core\entities\Customer\Website;

use core\queries\CodeQuery;
use kak\clickhouse\ActiveRecord;

/**
 * Class Code
 *
 * @package core\entities\Customer\Website
 *
 * @property int $website_id
 * @property int $block_count
 * @property int $block_width
 * @property string $direction
 * @property string $title_font
 * @property string $title_style
 * @property string $title_font_size
 * @property string $created_at
 *
 * @property Website $website
 */
class Code extends ActiveRecord
{
    // Fonts
    public const FONT_ARIAL = 'Arial';
    public const FONT_VERDANA = 'Verdana';
    public const FONT_CONSOLAS = 'Consolas';

    // Styles
    public const STYLE_NORMAL = 'normal';
    public const STYLE_LIGHT = 'lighter';
    public const STYLE_BOLD = 'bold';

    // Types
    public const DIRECTION_HORIZONTAL = 'horizontal';
    public const DIRECTION_VERTICAL = 'vertical';

    // Block count
    public const MIN_BLOCK_COUNT = 5;
    public const MAX_BLOCK_COUNT = 16;

    // Title sizes (px)
    public const MIN_TITLE_SIZE = 14;
    public const MAX_TITLE_SIZE = 18;

    // Block width
    public const MIN_BLOCK_WIDTH = 190;
    public const MAX_BLOCK_WIDTH = 230;

    public static function tableName(): string
    {
        return 'website_code';
    }

    public static function find(): CodeQuery
    {
        return new CodeQuery(self::class);
    }

    /**
     * @param int $website
     * @param int $blockCount
     * @param int $blockWidth
     * @param string $direction
     * @param string $titleFont
     * @param string $titleStyle
     * @param int $titleFontSize
     * @return Code
     */
    public static function create(
        int $website,
        int $blockCount,
        int $blockWidth,
        string $direction,
        string $titleFont,
        string $titleStyle,
        int $titleFontSize
    ): self
    {
        $code = new self;
        $code->website_id = $website;
        $code->block_count = $blockCount;
        $code->block_width = $blockWidth;
        $code->direction = $direction;
        $code->title_font = $titleFont;
        $code->title_style = $titleStyle;
        $code->title_font_size = $titleFontSize;

        return $code;
    }

    /**
     * @param int $blockCount
     * @param int $blockWidth
     * @param string $direction
     * @param string $titleFont
     * @param string $titleStyle
     * @param int $titleFontSize
     */
    public function edit(
        int $blockCount,
        int $blockWidth,
        string $direction,
        string $titleFont,
        string $titleStyle,
        int $titleFontSize
    ): void
    {
        $this->block_count = $blockCount;
        $this->block_width = $blockWidth;
        $this->direction = $direction;
        $this->title_font = $titleFont;
        $this->title_style = $titleStyle;
        $this->title_font_size = $titleFontSize;
    }

    public function getBlockCount(): int
    {
        return $this->block_count;
    }

    public function getBlockWidth(): int
    {
        return $this->block_width;
    }

    public function getTitleFont(): string
    {
        return $this->title_font;
    }

    public function getTitleStyle(): string
    {
        return $this->title_style;
    }

    public function getTitleFontSize(): int
    {
        return $this->title_font_size;
    }

    public function getCreatedAt(): string
    {
        return $this->created_at;
    }

    public function getWebsite()
    {
        return $this->hasOne(Website::class, ['id' => 'website_id']);
    }

    public function getWebsiteId(): int
    {
        return $this->website_id;
    }

    public function getDirection(): string
    {
        return $this->direction;
    }
}