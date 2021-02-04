<?php

namespace core\repositories\interfaces;

use core\queries\CodeQuery;
use core\exceptions\NotFoundException;
use core\entities\Customer\Website\{Website, Code};

/**
 * Interface CodeRepositoryInterface
 * @package core\repositories\interfaces
 */
interface CodeRepositoryInterface
{
    public function query(array $select = []): CodeQuery;
    /**
     * @param int $websiteId
     * @return Code
     * @throws NotFoundException
     */
    public function getByWebsite(int $websiteId): Code;
    public function save(Code $code): Code;
    public function removeByWebsite(Website $website): bool;
}