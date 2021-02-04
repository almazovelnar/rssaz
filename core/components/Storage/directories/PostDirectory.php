<?php

namespace core\components\Storage\directories;

use Yii;
use finfo;
use Exception;
use GuzzleHttp\Client;
use yii\helpers\FileHelper;
use League\ColorExtractor\Color;
use League\ColorExtractor\Palette;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Class PostDirectory
 * @package core\components\Storage\directories
 */
class PostDirectory extends Directory
{
    private const IMAGE_MIME_TYPES = [
        'image/jpg', 'image/jpeg', 'image/png', 'image/gif'
    ];

    private Client $client;

    public function __construct()
    {
        $this->client = new Client;
    }

    public function directory(): string
    {
        return 'posts';
    }

    /**
     * @param $url
     * @return array
     * @throws \yii\base\Exception|Exception
     */
    public function saveFromUrl($url)
    {
        $basename = time() . uniqid() . rand(100000,99999999) . '.jpg';

        $savePath = Yii::getAlias('@storage') . '/' . $this->directory();
        if (!is_dir($savePath)) FileHelper::createDirectory($savePath);

        $pathToImage = $savePath . '/' . $basename;
        if (!file_put_contents($pathToImage, $this->getImage($url)))
            throw new Exception('Can not save image file');

        return [
            'basename' => $basename,
            'color' => json_encode(Color::fromIntToRgb(key(
                Palette::fromFilename($pathToImage)->getMostUsedColors(1)
            ))),
        ];
    }

    /**
     * @param string|null $image
     * @return bool
     */
    public function remove(?string $image): bool
    {
        if (!$image) return false;

        $path = Yii::getAlias('@storage') . '/' . $this->directory() . '/' . $image;

        return file_exists($path) && FileHelper::unlink($path);
    }

    /**
     * @param string $url
     * @return string
     * @throws Exception
     */
    private function getImage(string $url): string
    {
        try {
            $request = $this->client->request('GET', $url, [
                'timeout' => 60, 'stream' => true, 'stream_context' => ['ssl' => ['allow_self_signed' => true]],
            ]);
        } catch (GuzzleException $e) {
            throw new Exception('Can not get the remote image: ' . $e->getMessage());
        }

        $content = $request->getBody()->getContents();
        $fileInfo = new finfo(FILEINFO_MIME_TYPE);

        if (($mimeType = $fileInfo->buffer($content)) === false)
            throw new Exception('Undefined file type');

        if (!in_array($mimeType, self::IMAGE_MIME_TYPES))
            throw new Exception('Not allowed file mime type: ' . $mimeType);

        return $content;
    }
}