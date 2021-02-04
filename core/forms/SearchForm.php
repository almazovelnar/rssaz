<?php

namespace core\forms;

use yii\base\Model;

/**
 * Class SearchForm
 * @package core\forms
 */
class SearchForm extends Model
{
    private const LIMIT = 12;

    public ?string $q = null;
    public int $page = 1;

    public function formName(): string
    {
        return '';
    }

    public function rules(): array
    {
        return [
            ['q', 'required'],
            ['q', 'string', 'min' => 3],
            ['page', 'number', 'min' => 1]
        ];
    }

    public function getRange(): int
    {
        return ($this->page - 1) * self::LIMIT;
    }

    public function getLimit(): int
    {
        return self::LIMIT;
    }
}
