<?php

namespace core\events;

use core\entities\Customer\Website\Rss;

/**
 * Class RssParseItemErrorDetected
 * @package core\events
 */
class RssParseItemErrorDetected
{
    private Rss $rss;

    public function __construct(Rss $rss)
    {
        $this->rss = $rss;
    }

    public function getRss(): Rss
    {
        return $this->rss;
    }
}
