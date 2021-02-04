<?php

namespace core\forms\manager\CustomerReview;

use yii\base\Model;
use core\entities\Customer\Review\Translation;

/**
 * Class TranslationForm
 *
 * @package core\forms\manager\CustomerReview
 */
class TranslationForm extends Model
{
    public string $language;
    public ?string $review = null;

    private Translation $_translation;

    /**
     * TranslationForm constructor.
     *
     * @param $language
     * @param Translation|null $translation
     * @param array $config
     */
    public function __construct(
        string $language,
        Translation $translation = null,
        array $config = []
    )
    {
        $this->language = $language;

        if ($translation) {
            $this->review = $translation->review;
            $this->_translation = $translation;
        }

        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            ['review', 'string', 'min' => 10],
        ];
    }

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function formName(): string
    {
        return parent::formName() . '_' . $this->language;
    }
}