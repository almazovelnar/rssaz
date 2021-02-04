<?php

namespace core\forms\manager\Category;

use core\entities\Category\Translation;
use core\forms\CompositeForm;
use core\forms\manager\MetaForm;
use core\validators\SlugValidator;

/**
 * Class TranslationForm
 * @package core\forms\manager\Category
 * @property MetaForm $meta
 */
class TranslationForm extends CompositeForm
{
    public $language;
    public $title;

    private $_translation;

    public function __construct($language, Translation $translation = null, array $config = [])
    {
        $this->language = $language;
        if ($translation) {
            $this->title = $translation->title;
            $this->meta = new MetaForm($language, $translation->meta);
            $this->_translation = $translation;
        } else {
            $this->meta = new MetaForm($language);
        }
        parent::__construct($config);
    }

    /**
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function formName()
    {
        return parent::formName() . '_' . $this->language;
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            ['title', 'required'],
            ['title', 'string', 'max' => 255],
        ];
    }

    /**
     * @return array of internal forms like ['meta', 'values']
     */
    protected function internalForms()
    {
        return ['meta'];
    }
}