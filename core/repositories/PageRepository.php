<?php

namespace core\repositories;

use core\entities\Page\Page;
use core\exceptions\NotFoundException;

/**
 * Class PageRepository
 * @package core\repositories
 */
class PageRepository extends AbstractRepository
{
    /**
     * @param array $condition
     * @return array|null|\yii\db\ActiveRecord
     * @throws NotFoundException
     */
    protected function getBy(array $condition)
    {
        if (!($page = Page::find()->andWhere($condition)->limit(1)->one())) {
            throw new NotFoundException('Page not found !');
        }

        return $page;
    }
}