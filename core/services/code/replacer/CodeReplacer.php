<?php

namespace core\services\code\replacer;

use core\services\code\CodeDto;
use core\services\code\replacer\Exceptions\ReplacerException;
use core\services\code\replacer\Replacers\{BlockReplacer, DirectionReplacer, UrlReplacer, StyleReplacer};

/**
 * Class CodeReplacer
 * @package core\services\code\replacer
 */
class CodeReplacer
{
    public CodeDto $dto;
    protected array $triggers = [
        'blockId'    => BlockReplacer::class,
        'urlToData'  => UrlReplacer::class,
        'cssOptions' => StyleReplacer::class,
        'bannersDirection' => DirectionReplacer::class
    ];

    public function setDto(CodeDto $codeDto): self
    {
        $this->dto = $codeDto;
        return $this;
    }

    /**
     * @param string $content
     * @return string
     * @throws ReplacerException
     */
    public function replace(string $content): string
    {
        foreach ($this->triggers as $template => $trigger) {
            $replacer = new $trigger;
            if (!($replacer instanceof ReplacerInterface))
                throw new ReplacerException('Class ' . get_class($replacer) . ' is not replacer !');
            $content = $replacer->handle($template, $content, $this);
        }

        return $content;
    }
}