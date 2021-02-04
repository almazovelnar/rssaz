<?php

namespace core\entities;

use core\queries\ImageQuery;
use kak\clickhouse\ActiveRecord;

/**
 * Class Image
 * @package core\entities
 *
 * @property string $hash
 * @property string $filename
 * @property string $color
 * @property string $created_at
 */
class Image extends ActiveRecord
{
    public static function tableName(): string
    {
        return 'images';
    }

    public static function find(): ImageQuery
    {
        return new ImageQuery(self::class);
    }

    public static function create(
        string $hash,
        string $filename,
        string $color
    ): self
    {
        $image = new self();
        $image->hash = $hash;
        $image->filename = $filename;
        $image->color = $color;
        return $image;
    }

    public function getHash(): string
    {
        return $this->hash;
    }

    public function getFilename(): string
    {
        return $this->filename;
    }

    public function getColor(): string
    {
        return $this->color;
    }
}
