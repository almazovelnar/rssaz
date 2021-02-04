<?php

namespace core\events;

use core\services\parser\ParserDto;

/**
 * Class RssParseFinished
 * @package core\events
 */
class RssParseFinished
{
    private ParserDto $parserDto;

    public function __construct(ParserDto $parserDto)
    {
        $this->parserDto = $parserDto;
    }

    public function getParserDto(): ParserDto
    {
        return $this->parserDto;
    }
}
