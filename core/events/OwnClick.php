<?php

namespace core\events;

use core\entities\Customer\Website\Post;

/**
 * Class OwnClick
 * @package core\events
 */
class OwnClick
{
    private Post $post;
    private ?string $referrer;

    public function __construct(Post $post, ?string $referrer)
    {
        $this->post = $post;
        $this->referrer = $referrer;
    }

    public function getPost(): Post
    {
        return $this->post;
    }

    public function getReferrer(): ?string
    {
        return $this->referrer;
    }
}