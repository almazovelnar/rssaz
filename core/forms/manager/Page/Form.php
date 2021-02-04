<?php

namespace core\forms\manager\Page;

use core\validators\SlugValidator;
use Yii;
use core\entities\Page\Page;
use core\forms\CompositeForm;

/**
 * Class Form
 * @package core\forms\manager\Page
 * @property TranslationForm[] $translations
 */
class Form extends CompositeForm
{
    /**
     * @var string
     */
    public $slug;
    /**
     * @var string
     */
    public $type;
    /**
     * @var bool
     */
    public $status;
    /**
     * @var bool
     */
    public $show;

    private $_page;

    /**
     * Form constructor.
     * @param Page|null $page
     * @param array $config
     */
    public function __construct(Page $page = null, array $config = [])
    {
        $translations = [];

        if ($page) {
            $this->slug = $page->slug;
            $this->status = $page->status;
            $this->show = $page->show;
            $this->type = $page->type;
            $this->_page = $page;
            foreach (Yii::$app->params['languages'] as $code => $label) {
                $translations[] = new TranslationForm($code, $page->getTranslation($code));
            }
        } else {
            foreach (Yii::$app->params['languages'] as $code => $label) {
                $translations[] = new TranslationForm($code);
            }
        }

        $this->translations = $translations;

        parent::__construct($config);
    }

    /**
     * @return array of internal forms like ['meta', 'values']
     */
    protected function internalForms(): array
    {
        return ['translations'];
    }

    /**
     * @return array of list for page types
     */
    public static function getTypesList(): array
    {
        return [
            Page::TYPE_FRONTEND => 'Frontend',
            Page::TYPE_CABINET => 'Cabinet',
        ];
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['status', 'show', 'slug'], 'required'],
            [['status', 'show'], 'integer'],
            ['type', 'in', 'range' => array_keys(self::getTypesList())],
            ['slug', SlugValidator::class],
            [
                'slug',
                'unique',
                'targetClass' => Page::class,
                'when' => function () {
                    return is_null($this->_page);
                }
            ]
        ];
    }
}