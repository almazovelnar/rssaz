<?php

namespace core\services\code\replacer\Replacers;

use core\services\code\replacer\CodeReplacer;
use core\services\code\replacer\ReplacerInterface;

/**
 * Class StyleReplacer
 * @package core\services\code\replacer\Replacers
 */
class StyleReplacer implements ReplacerInterface
{
    /**
     * @param string $template
     * @param string $content
     * @param CodeReplacer $replacer
     * @return string
     */
    public function handle(string $template, string $content, CodeReplacer $replacer): string
    {
        $replace = json_encode($replacer->dto->getStyleAttributes());

        return str_replace("{{$template}}", addslashes($replace), $content);
    }
}