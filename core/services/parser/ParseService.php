<?php

namespace core\services\parser;

use Yii;
use Exception;
use core\dispatchers\EventDispatcher;
use core\exceptions\RssParseException;
use core\entities\Customer\Website\{Rss, Post};
use core\events\{ParseFinished, RssParseErrorDetected, RssParseFinished};

/**
 * Class ParseService
 * @package core\services\parser
 */
class ParseService
{
    private EventDispatcher $eventDispatcher;
    private ParserDto $parserDto;

    public function __construct(EventDispatcher $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    public function handle(Rss $rss): bool
    {
        try {
            /** @var RssParser $rssParser */
            $rssParser = Yii::$container->get(RssParser::class);
            $this->parserDto = $rssParser->parse($rss);
            $this->eventDispatcher->dispatch(new RssParseFinished($this->parserDto));
            return true;
        } catch (RssParseException $e) {
            $this->eventDispatcher->dispatch(new RssParseErrorDetected($rss, $e));
        } catch (Exception $e) {
            Yii::$app->errorHandler->logException($e);
        }
        return false;
    }

    public function flush(): void
    {
        try {
            $parsedPosts = $this->parserDto->getParsedPosts();
            if ($parsedPosts->isEmpty()) return;

            $rows = [];
            $columns = array_keys($parsedPosts->first()->getDirtyAttributes());
            foreach ($parsedPosts as $post)
                array_push($rows, array_values($post->getDirtyAttributes()));
            Post::getDb()->createCommand()->batchInsert(Post::tableName(), $columns, $rows)->execute();
            $this->eventDispatcher->dispatch(new ParseFinished($this->parserDto));
        } catch (Exception $e) {
            Yii::$app->errorHandler->logException($e);
        }
    }
}
