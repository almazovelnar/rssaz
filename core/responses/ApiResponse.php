<?php

namespace core\responses;

use Yii;
use yii\web\Request;
use yii\helpers\StringHelper;
use core\entities\Session\Session;
use core\entities\Customer\Website\Post;
use core\helpers\{WebsiteHelper, CodeHelper};

/**
 * Class ApiResponse
 * @package core\responses
 */
class ApiResponse implements Responsable
{
    private Session $session;
    private array $posts;
    private string $lang;

    public function __construct(
        Session $session,
        array $posts,
        string $lang
    )
    {
        $this->session = $session;
        $this->posts = $posts;
        $this->lang = $lang;
    }

    public function toResponse(Request $request)
    {
        $response = ['sid' => $this->session->getId(), 'blocks' => []];
        /** @var Post $post */
        foreach ($this->posts as $post) {
            $response['blocks'][] = [
                'url'   => WebsiteHelper::generateRedirectUrl($this->lang, ['id' => $post->id, 'sid' => $this->session->id]),
                'title' => StringHelper::truncate($post->title, 70),
                'image' => Yii::$app->storage->post->getThumb(300, $post->image),
                'color' => CodeHelper::parseColor($post->color),
            ];
        }

        return json_encode($response, JSON_UNESCAPED_UNICODE);
    }
}