<?php

namespace core\events;

/**
 * Class OwnView
 * @package core\events
 */
class OwnView
{
    private array $posts;

    public function __construct(array $posts)
    {
        $this->posts = $posts;
    }

    public function getPosts(): array
    {
        return $this->posts;
    }
}