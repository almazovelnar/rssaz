<?php

namespace core\validators;

use yii\validators\RegularExpressionValidator;

/**
 * Class SlugValidator
 * @package core\validators
 */
class SlugValidator extends RegularExpressionValidator
{
    /**
     * @var string
     */
    public $pattern = '#^[a-z0-9-]*$#s';
    /**
     * @var string
     */
    public $message = 'Некорректная ссылка';
}