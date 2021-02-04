<?php

namespace core\events;

use core\entities\Customer\Website\Code;

/**
 * Class CodeGenerated
 * @package core\events
 */
class CodeGenerated
{
    private Code $code;

    public function __construct(Code $code)
    {
        $this->code = $code;
    }

    public function getCode(): Code
    {
        return $this->code;
    }
}
