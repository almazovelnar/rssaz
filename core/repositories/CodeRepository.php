<?php

namespace core\repositories;

use RuntimeException;
use core\queries\CodeQuery;
use kak\clickhouse\ActiveRecord;
use yii\base\InvalidConfigException;
use core\exceptions\NotFoundException;
use core\entities\Customer\Website\{Website, Code};
use core\repositories\interfaces\CodeRepositoryInterface;

/**
 * Class CodeRepository
 * @package core\repositories
 */
class CodeRepository implements CodeRepositoryInterface
{
    /**
     * @param int $websiteId
     * @return Code|ActiveRecord
     * @throws NotFoundException|InvalidConfigException
     */
    public function getByWebsite(int $websiteId): Code
    {
        return $this->query()
            ->with(['website'])
            ->filter(['website' => $websiteId])
            ->firstOrFail();
    }

    public function all(array $filters = []): array
    {
        return $this->query()->filter($filters)->get();
    }

    public function query(array $select = []): CodeQuery
    {
        return Code::find()
            ->from('website_code wc')
            ->select($select);
    }

    public function update(int $websiteId, array $fields)
    {
       return Code::find()->updateRecord('website_code', $fields, ['website_id' => $websiteId]);
    }

    public function save(Code $code): Code
    {
        if (!$code->insert())
            throw new RuntimeException("Can't save code !");
        return $code;
    }

    public function removeByWebsite(Website $website): bool
    {
        return Code::find()->deleteRecord('website_code', ['website_id' => $website->id]);
    }
}