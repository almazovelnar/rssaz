<?php

namespace core\entities;

/**
 * Class Meta
 * @package core\entities
 */
class Meta
{
    /**
     * @var
     */
    public $title;
    /**
     * @var
     */
    public $description;
    /**
     * @var
     */
    public $keywords;

    /**
     * Meta constructor.
     * @param $title
     * @param $description
     * @param $keywords
     */
    public function __construct($title, $description, $keywords)
    {
        $this->title = $title;
        $this->description = $description;
        $this->keywords = $keywords;
    }
}