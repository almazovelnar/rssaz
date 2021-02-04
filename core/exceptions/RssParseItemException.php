<?php

namespace core\exceptions;

use Exception;
use Serializable;
use SimplePie_Item;

/**
 * Class RssParseItemException
 * @package core\exceptions
 */
class RssParseItemException extends Exception implements Serializable
{
    private string $field;
    private SimplePie_Item $item;

    public function getField(): string
    {
        return $this->field;
    }

    public function __construct(
        string $field,
        SimplePie_Item $item,
        string $message = '',
        int $code = 0,
        $previous = null
    )
    {
        parent::__construct($message, $code, $previous);

        $this->field = $field;
        $this->item = $item;
    }

    public function serialize()
    {
        return serialize([$this->field, $this->item, $this->code, $this->message]);
    }

    public function unserialize($serialized)
    {
        [$this->field, $this->item, $this->code, $this->message] = unserialize($serialized);
    }

    public function getItem()
    {
        return $this->item;
    }
}
