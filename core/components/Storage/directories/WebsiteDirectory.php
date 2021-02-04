<?php

namespace core\components\Storage\directories;

use Yii;
use Exception;
use yii\helpers\FileHelper;
use core\entities\Customer\Website\Website;

class WebsiteDirectory extends Directory
{
    public function directory(): string
    {
        return 'website';
    }

    public function saveIcon(Website $website, string $iconUrl): bool
    {
        $basename = $website->name . '.ico';
        $savePath = Yii::getAlias('@frontend') . '/web/images/favs/';
        if (!is_dir($savePath))
            FileHelper::createDirectory($savePath);

        $parsedUrlIcon = parse_url($iconUrl);

        $iconNewUrl = $parsedUrlIcon['path'];
        $iconNewUrl .= isset($parsedUrlIcon['query']) ? '?'. $parsedUrlIcon['query'] : '';

        if (!file_put_contents($savePath . '/' . $basename, $this->getIcon($website->address . $iconNewUrl))){
            throw new Exception('Can not save ico file');
        };

        return true;
    }

    private function getIcon(string $url)
    {
        $context = stream_context_create([
            "ssl" => [
                "allow_self_signed" => true,
                "verify_peer" => false,
                "verify_peer_name" => false,
            ],
        ]);

        if (($source = file_get_contents($url, false, $context)) === false) {
            throw new Exception('Ico does not exist');
        }

        return $source;
    }
}