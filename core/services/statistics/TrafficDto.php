<?php

namespace core\services\statistics;

/**
 * Class TrafficDto
 * @package core\services\statistics
 */
class TrafficDto
{
    private int $views = 0;
    private int $clicks = 0;

    public function setViews(int $views): self
    {
        $this->views = $views;

        return $this;
    }

    public function setClicks(int $clicks): self
    {
        $this->clicks = $clicks;

        return $this;
    }

    public function hasAnyViews(): bool
    {
        return $this->views > 0;
    }

    public function getViews(): int
    {
        return $this->views;
    }

    public function getClicks(): int
    {
        return $this->clicks;
    }
}
