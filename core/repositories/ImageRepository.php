<?php

namespace core\repositories;

use RuntimeException;
use core\entities\Image;
use core\queries\ImageQuery;
use core\repositories\interfaces\ImageRepositoryInterface;

/**
 * Class ImageRepository
 * @package core\repositories
 */
class ImageRepository implements ImageRepositoryInterface
{
    public function getByHash(array $filter = [])
    {
        return $this->query(["i.hash", "i.filename", "i.color"])
            ->forToday()
            ->filter($filter)
            ->first();
    }

    public function all(array $filters = [])
    {
        return $this->query(["i.hash", "i.filename", "i.color"])
            ->filter($filters)
            ->createCommand()->getRawSql();
    }

    public function query(array $select = []): ImageQuery
    {
        return Image::find()
            ->from("images i")
            ->select($select);
    }

    public function save(Image $image): Image
    {
        if (!$image->insert())
            throw new RuntimeException("Can't save image.");
        return $image;
    }

    public function getByFilename(string $filename)
    {
        return $this->query()
            ->filter(['filename' => $filename])
            ->firstOrFail();
    }

    public function remove(Image $image): bool
    {
        return Image::find()->deleteRecord('images', ['filename' => $image->getFilename()]);
    }
}