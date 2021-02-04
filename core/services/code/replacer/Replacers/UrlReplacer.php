<?php

namespace core\services\code\replacer\Replacers;

use Yii;
use core\services\code\replacer\CodeReplacer;
use core\services\code\replacer\ReplacerInterface;

/**
 * Class UrlReplacer
 * @package core\components\CodeGenerator\Replacers
 */
class UrlReplacer implements ReplacerInterface
{
    /**
     * @param string $template
     * @param string $content
     * @param CodeReplacer $replacer
     * @return string
     */
    public function handle(string $template, string $content, CodeReplacer $replacer): string
    {
        return str_replace(
            "{{$template}}",
            Yii::$app->apiUrlManager->createAbsoluteUrl(['data/get', 'hash' => $replacer->dto->getWebsite()->getHash()], true),
            $content
        );
    }
}