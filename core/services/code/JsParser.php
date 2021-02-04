<?php

namespace core\services\code;

use Yii;
use core\services\code\replacer\CodeReplacer;

/**
 * Class JsParser
 * @package core\services\code
 */
class JsParser
{
    private CodeReplacer $codeReplacer;

    public function __construct(CodeReplacer $codeReplacer)
    {
        $this->codeReplacer = $codeReplacer;
    }

    /**
     * @param CodeDto $dto
     * @return string
     * @throws replacer\Exceptions\ReplacerException
     */
    public function write(CodeDto $dto): string
    {
        return $this->codeReplacer
            ->setDto($dto)
            ->replace(file_get_contents(Yii::getAlias('@cabinet') . '/web/js/generated.js'));
    }
}