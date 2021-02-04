<?php

namespace core\events;

use core\forms\ClickForm;
use core\entities\Customer\Website\Post;

/**
 * Class Click
 * @package core\events
 */
class Click
{
    private ClickForm $form;
    private Post $post;
    private string $ip;

    public function __construct(Post $post, ClickForm $form, string $ip)
    {
        $this->form = $form;
        $this->post = $post;
        $this->ip = $ip;
    }

    public function getForm(): ClickForm
    {
        return $this->form;
    }

    public function getPost(): Post
    {
        return $this->post;
    }

    public function getIp(): string
    {
        return $this->ip;
    }
}
