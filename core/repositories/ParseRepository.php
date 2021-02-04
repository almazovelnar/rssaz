<?php

namespace core\repositories;

use core\entities\Parse\Parse;
use core\exceptions\NotFoundException;
use core\entities\Customer\Website\Website;

/**
 * Class ParseRepository
 * @package core\repositories
 */
class ParseRepository extends AbstractRepository
{

    /**
     * @param array $condition
     * @return array|null|\yii\db\ActiveRecord
     * @throws NotFoundException
     */
    protected function getBy(array $condition)
    {
        if (!($parse = Parse::find()->andWhere($condition)->limit(1)->one())) {
            throw new NotFoundException('Parse not found !');
        }

        return $parse;
    }

    public function all(array $condition)
    {
        return Parse::find()->andWhere($condition)->all();
    }

    public function removeByWebsite(Website $website)
    {
        return Parse::deleteAll(['website_id' => $website->id]);
    }
}