<?php

namespace core\repositories\interfaces;

use core\queries\RssQuery;
use core\entities\Customer\Website\{Website, Rss};

/**
 * Interface RssRepositoryInterface
 * @package core\repositories\interfaces
 */
interface RssRepositoryInterface
{
    public function query(array $select = []): RssQuery;
    public function save(Rss $rss): Rss;
    public function removeByWebsite(Website $website): bool;
}