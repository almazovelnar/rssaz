<?php

namespace core\listeners;

use Yii;
use yii\db\Exception;
use core\events\ParseFinished;
use core\entities\Customer\Website\Post;

/**
 * Class ParseFinishedStoringPostsListener
 * @package core\listeners
 */
class ParseFinishedStoringPostsListener
{
    /**
     * @param ParseFinished $event
     */
    public function handle(ParseFinished $event): void
    {
        $collection = $event->getParserDto()->getParsedPosts();
        if ($collection->isEmpty()) return;

        $columns = array_keys($collection->first()->getDirtyAttributes());
        $rows = [];

        foreach ($collection as $post) array_push($rows, array_values($post->getDirtyAttributes()));

        try {
            Post::getDb()->createCommand()->batchInsert(Post::tableName(), $columns, $rows)->execute();
        } catch (Exception $e) {
            Yii::$app->errorHandler->logException($e);
        }
    }
}