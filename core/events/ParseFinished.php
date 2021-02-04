<?php

namespace core\events;

use core\services\parser\ParserDto;

/**
 * Class ParseFinished
 * @package core\events
 */
class ParseFinished
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
