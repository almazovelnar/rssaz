<?php

namespace core\services\code;

use core\entities\Customer\Website\Website;

/**
 * Class CodeDto
 * @package core\services\code
 */
class CodeDto
{
    private int $blockCount;
    private int $blockWidth;
    private string $direction;
    private string $titleFont;
    private string $titleStyle;
    private int $titleFontSize;
    private Website $website;

    public function __construct(
        int $blockCount,
        int $blockWidth,
        string $direction,
        string $titleFont,
        string $titleStyle,
        int $titleFontSize
    )
    {
        $this->blockCount = $blockCount;
        $this->blockWidth = $blockWidth;
        $this->direction = $direction;
        $this->titleFont = $titleFont;
        $this->titleStyle = $titleStyle;
        $this->titleFontSize = $titleFontSize;
    }

    public function getBlockCount(): int
    {
        return $this->blockCount;
    }

    public function getBlockWidth(): int
    {
        return $this->blockWidth;
    }

    public function getTitleFont(): string
    {
        return $this->titleFont;
    }

    public function getTitleStyle(): string
    {
        return $this->titleStyle;
    }

    public function getTitleFontSize(): int
    {
        return $this->titleFontSize;
    }

    public function getDirection(): string
    {
        return $this->direction;
    }

    public function getWebsite(): Website
    {
        return $this->website;
    }

    public function setWebsite(Website $website): void
    {
        $this->website = $website;
    }

    public function getStyleAttributes(): array
    {
        return [
            'fontFamily' => $this->titleFont,
            'fontWeight' => $this->titleStyle,
            'fontSize' => "{$this->titleFontSize}px",
            'width' => "{$this->blockWidth}px",
        ];
    }
}