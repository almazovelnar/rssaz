<?php

namespace core\forms\cabinet;

use Exception;
use yii\base\Model;

/**
 * Class ValidatorForm
 * @package core\forms\cabinet
 */
class ValidatorForm extends Model
{
    public ?string $link = null;

    public function formName(): string
    {
        return '';
    }

    public function rules(): array
    {
        return [
            ['link', 'required'],
            ['link', 'url'],
        ];
    }

    public function getLink(): string
    {
        return $this->link;
    }

    public function afterValidate()
    {
        try {
            if (!file_get_contents($this->link))
                $this->addError('link', 'Page not found');
        } catch (Exception $e) {
            $this->addError('link', 'Page not found');
        }

        return !$this->hasErrors();
    }
}
