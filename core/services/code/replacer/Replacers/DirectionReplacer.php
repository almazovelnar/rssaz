<?php

namespace core\services\code\replacer\Replacers;

use core\services\code\replacer\CodeReplacer;
use core\services\code\replacer\ReplacerInterface;

/**
 * Class DirectionReplacer
 * @package core\services\code\replacer\Replacers
 */
class DirectionReplacer implements ReplacerInterface
{
    /**
     * @param string $template
     * @param string $content
     * @param CodeReplacer $replacer
     * @return string
     */
    public function handle(string $template, string $content, CodeReplacer $replacer): string
    {
        return str_replace("{{$template}}", $replacer->dto->getDirection(), $content);
    }
}