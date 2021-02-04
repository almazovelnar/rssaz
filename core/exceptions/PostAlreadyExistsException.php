<?php

namespace core\exceptions;

use Exception;
use core\entities\Customer\Website\Post;

/**
 * Class PostAlreadyExistsException
 * @package core\exceptions
 */
class PostAlreadyExistsException extends Exception
{
    private Post $post;

    public function __construct(string $message, Post $post, int $code = 0, $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->post = $post;
    }

    public function getPost(): Post
    {
        return $this->post;
    }
}
