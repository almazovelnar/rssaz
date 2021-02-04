<?php

namespace core\listeners;

use core\events\OwnView;
use core\entities\Statistics\Raw;
use core\repositories\WebsiteRepository;

class OwnViewListener
{
    /**
     * @var WebsiteRepository
     */
    private $websites;

    private $db;

    public function __construct(WebsiteRepository $websites)
    {
        $this->db = \Yii::$app->db;
        $this->websites = $websites;
    }

    public function handle(OwnView $view)
    {
        $website = $this->websites->getOwn();
        $posts = $view->getPosts();
        $request = $view->getRequest();

        $rawData = [];
        $postIds = [];
        foreach ($posts as $post) {
            if (is_array($post)) {
                $rawData[] = [$post['website_id'], $website->id, $post['id'], $request->ip, Raw::TYPE_VIEW];
                $postIds[] = $post['id'];
            } else {
                $rawData[] = [$post->website_id, $website->id, $post->id, $request->ip, Raw::TYPE_VIEW];
                $postIds[] = $post->id;
            }
        }

        try {
            $this->db
                ->createCommand()
                ->batchInsert(
                    'raw_statistics',
                    ['source_website_id', 'recipient_website_id', 'post_id', 'ip', 'type'],
                    $rawData
                )
                ->execute();
            $this->db->createCommand('UPDATE website_posts SET views = views + 1 WHERE id IN (' . implode(',', $postIds) . ')')->execute();
        } catch (\Exception $e) {
            \Yii::$app->errorHandler->logException($e);
        }
    }
}