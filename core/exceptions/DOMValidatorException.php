<?php

namespace core\exceptions;

use Exception;
use Throwable;

/**
 * Class DOMValidatorException
 * @package core\exceptions
 */
class DOMValidatorException extends Exception
{
    private int $libXmlErrorCode;

    public function __construct(
        string $message,
        int $libXmlErrorCode = LIBXML_ERR_WARNING,
        $code = 0,
        Throwable $previous = null
    )
    {
        parent::__construct($message, $code, $previous);

        $this->libXmlErrorCode = $libXmlErrorCode;
    }

    public function getLibXmlErrorCode(): int
    {
        return $this->libXmlErrorCode;
    }
}