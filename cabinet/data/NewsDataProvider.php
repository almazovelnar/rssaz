<?php

namespace cabinet\data;

use yii\data\ActiveDataProvider;
use core\entities\Customer\Website\Website;

/**
 * Class NewsDataProvider
 * @package cabinet\data
 * @property Website $website
 */
class NewsDataProvider extends ActiveDataProvider
{
    public ?Website $website = null;
}