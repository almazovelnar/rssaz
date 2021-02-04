<?php

namespace core\components\Storage\directories;

use yii\helpers\FileHelper;
use yii\web\UploadedFile;
use Yii;

abstract class Directory
{
    const ALIAS_RELATIVE = '@static';
    const ALIAS_ABSOLUTE = '@storage';

    /**
     * @return string
     */
    abstract public function directory(): string;

    /**
     * @param int $size
     * @param string|null $basename
     * @return string
     */
    public function getThumb(int $size, ?string $basename = null): string
    {
        return $this->getFile($this->getBasenameWithWidth($basename, $size));
    }

    /**
     * @param string|null $basename
     * @param string $alias
     * @return string
     */
    public function getFile(?string $basename, string $alias = self::ALIAS_RELATIVE)
    {
        return Yii::getAlias($alias) . '/' . $this->directory() . '/' . $basename;
    }

    /**
     * @param UploadedFile $file
     * @param string|null $basename
     * @return string
     * @throws \yii\base\Exception
     */
    public function save(UploadedFile $file, string $basename = null)
    {
        $savePath = Yii::getAlias('@storage') . '/' . $this->directory();
        if (!file_exists($savePath)) {
            FileHelper::createDirectory($savePath);
        }
        if (!$basename) {
            $filename = uniqid();
        } else {
            $filename = explode('.', $basename)[0];
        }

        $basename = $filename . '.' . $file->getExtension();
        $file->saveAs($savePath . '/' . $basename);

        return $basename;
    }

    /**
     * @param string $basename
     */
    public function delete(string $basename)
    {
        $file = $this->getFile($basename, self::ALIAS_ABSOLUTE);
        if (file_exists($file)) {
            FileHelper::unlink($file);
        }
    }

    /**
     * @param $basename
     * @param $width
     * @return string
     */
    private function getBasenameWithWidth($basename, $width)
    {
        $fileInfo = pathinfo($basename);
        return $fileInfo['filename'] . '_' . $width . '.' . $fileInfo['extension'];
    }
}