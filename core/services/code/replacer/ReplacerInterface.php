<?php

namespace core\services\code\replacer;

/**
 * Interface ReplacerInterface
 * @package core\services\code\replacer
 */
interface ReplacerInterface
{
    public function handle(string $template, string $content, CodeReplacer $replacer): string;
}