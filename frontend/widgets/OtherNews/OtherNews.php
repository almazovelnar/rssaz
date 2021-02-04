<?php

namespace frontend\widgets\OtherNews;

use yii\base\Widget;

/**
 * Class OtherNews
 * @package frontend\widgets\OtherNews
 */
class OtherNews extends Widget
{
    public array $posts;

    /**
     * @return string
     */
    public function run()
    {
        return $this->render('index', [
            'posts' => $this->posts,
        ]);
    }
}