<?php

namespace core\forms\manager\Page;

use core\forms\CompositeForm;
use core\forms\manager\MetaForm;
use core\entities\Page\Translation;

/**
 * Class TranslationForm
 *
 * @package core\forms\manager\Page
 * @property MetaForm $meta
 */
class TranslationForm extends CompositeForm
{
    /**
     * @var string
     */
    public $language;
    /**
     * @var string
     */
    public $title;
    /**
     * @var string
     */
    public $description;

    /**
     * @var Translation
     */
    private $_translation;

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
            $this->title = $translation->title;
            $this->description = $translation->description;
            $this->meta = new MetaForm($language, $translation->meta);
            $this->_translation = $translation;
        } else {
            $this->meta = new MetaForm($language);
        }

        parent::__construct($config);
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            ['title', 'required'],
            ['title', 'string', 'max' => 255],
            ['description', 'string'],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'title' => 'Səhifə başlığı',
            'description'  => 'Səhifə kontent',
        ];
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
     * @return array of internal forms like ['meta', 'values']
     */
    protected function internalForms()
    {
        return ['meta'];
    }
}