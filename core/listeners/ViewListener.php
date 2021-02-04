<?php

namespace core\listeners;

use Yii;
use Exception;
use yii\db\Connection;
use core\events\View;
use core\entities\Statistics\Raw;
use core\entities\Customer\Website\Post;
use kak\clickhouse\Connection as ClickHouseConnection;
use core\repositories\interfaces\PostRepositoryInterface;

/**
 * Class ViewListener
 * @package core\listeners
 */
class ViewListener
{
    private Connection $mysql;
    private ClickHouseConnection $clickHouse;
    private PostRepositoryInterface $postRepository;

    public function __construct(PostRepositoryInterface $postRepository)
    {
        $this->mysql = Yii::$app->db;
        $this->clickHouse = Yii::$app->clickhouse;
        $this->postRepository = $postRepository;
    }

    public function handle(View $view)
    {
        $session = $view->getSession();
        $posts = $session->getPosts();
        $batchData = [];

        /** @var Post $post */
        foreach ($this->postRepository->getByIds($posts, ['id', 'website_id']) as $post)
            $batchData[] = [$session->website_id, $post->website_id, $post->id, Raw::TYPE_VIEW, Raw::REFERRER_TYPE_BANNERS, $session->algorithm];

        try {
            $this->mysql->createCommand()
                ->batchInsert('raw_statistics', ['source_website_id', 'recipient_website_id', 'post_id', 'type', 'referrer_type', 'algorithm'], $batchData)
                ->execute();

            $this->postRepository->increment('views', $posts);
            $this->postRepository->calculateCtr($posts);
        } catch (Exception $e) {
            Yii::$app->errorHandler->logException($e);
        }
    }
}