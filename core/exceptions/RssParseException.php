<?php

namespace core\exceptions;

use Exception;
use Serializable;

/**
 * Class RssParseException
 * @package core\exceptions
 */
class RssParseException extends Exception implements Serializable
{
    protected array $errors;
    private ?string $xmlContent;

    public function __construct(
        string $message = "",
        array $errors = [],
        ?string $xmlContent = null,
        int $code = 0,
        $previous = null
    )
    {
        parent::__construct($message, $code, $previous);

        $this->errors = $errors;
        $this->xmlContent = $xmlContent;
    }

    public function serialize()
    {
        return serialize([$this->code, $this->message, $this->errors, $this->xmlContent]);
    }

    public function unserialize($serialized)
    {
        [$this->code, $this->message, $this->errors, $this->xmlContent] = unserialize($serialized);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function getXmlContent(): ?string
    {
        return $this->xmlContent;
    }
}
