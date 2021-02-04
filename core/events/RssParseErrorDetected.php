<?php

namespace core\events;

use core\entities\Customer\Website\Rss;
use core\exceptions\RssParseException;

/**
 * Class RssParseErrorDetected
 * @package core\events
 */
class RssParseErrorDetected
{
    private Rss $rss;
    private RssParseException $exception;

    public function __construct(Rss $rss, RssParseException $exception)
    {
        $this->rss = $rss;
        $this->exception = $exception;
    }

    public function getRss(): Rss
    {
        return $this->rss;
    }

    public function getException(): RssParseException
    {
        return $this->exception;
    }
}
