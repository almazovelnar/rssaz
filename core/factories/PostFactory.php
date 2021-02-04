<?php

namespace core\factories;

use core\entities\Customer\Website\Post;

/**
 * Class PostFactory
 */
class PostFactory
{
    public static function create(
        int $id,
        int $websiteId,
        int $rssId,
        int $categoryId,
        string $lang,
        string $guid,
        string $title,
        string $link,
        string $description,
        string $image,
        string $color,
        string $status,
        string $publishDate
    ): Post
    {
        $post = new Post();
        $post->id = $id;
        $post->website_id = $websiteId;
        $post->rss_id = $rssId;
        $post->category_id = $categoryId;
        $post->lang = $lang;
        $post->guid = $guid;
        $post->title = $title;
        $post->link = $link;
        $post->description = $description;
        $post->image = $image;
        $post->color = $color;
        $post->status = $status;
        $post->created_at = $publishDate;
        $post->parsed_at = date('Y-m-d H:i:s');

        return $post;
    }
}
