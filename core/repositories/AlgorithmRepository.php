<?php

namespace core\repositories;

use core\queries\AlgorithmQuery;
use core\entities\Customer\Website\{Website, Algorithm};
use core\repositories\interfaces\AlgorithmRepositoryInterface;

/**
 * Class AlgorithmRepository
 * @package core\repositories
 */
class AlgorithmRepository implements AlgorithmRepositoryInterface
{
    public function query(array $select = []): AlgorithmQuery
    {
        return Algorithm::find()
            ->from("website_algorithms")
            ->select($select);
    }

    public function all(array $filters = []): array
    {
        return $this->query()
            ->filter($filters)
            ->get();
    }

    public function removeByWebsite(Website $website): bool
    {
        return Algorithm::find()->deleteRecord('website_algorithms', ['website_id' => $website->id]);
    }
}
