<?php

namespace frontend\widgets\Actual;

use yii\base\Widget;

/**
 * Class Actual
 * @package frontend\widgets\Actual
 */
class Actual extends Widget
{
    public array $posts;

    public function run()
    {
        $posts = $this->posts;

        if (!empty($posts)) {
            $count = count($posts);
            $limit = ($count >= 4) ? 4 : $count;
            $randIndexes = array_rand($posts, $limit);
            $posts = array_intersect_key($posts, array_flip(array_wrap($randIndexes)));
        }

        return $this->render('index', [
            'posts' => $posts,
        ]);
    }
}