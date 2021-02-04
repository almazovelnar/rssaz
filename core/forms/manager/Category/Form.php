<?php

namespace core\forms\manager\Category;

use core\entities\Category\Category;
use core\forms\CompositeForm;
use core\validators\SlugValidator;
use Yii;

/**
 * Class CreateForm
 * @package core\forms\manager\Category
 * @property TranslationForm[] $translations
 */
class Form extends CompositeForm
{
    public $name;
    public $slug;
    public $parentId;
    public $status;
    public $showInMenu;

    private $_category;

    public function __construct(Category $category = null, array $config = [])
    {
        $translations = [];
        if ($category) {
            $this->name = $category->name;
            $this->parentId = $category->parent->id;
            $this->slug = $category->slug;
            $this->status = $category->status;
            $this->showInMenu = $category->show_in_menu;
            $this->_category = $category;
            foreach (Yii::$app->params['languages'] as $code => $label) {
                $translations[] = new TranslationForm($code, $category->getTranslation($code));
            }
        } else {
            foreach (Yii::$app->params['languages'] as $code => $label) {
                $translations[] = new TranslationForm($code);
            }
        }
        $this->translations = $translations;
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['name', 'parentId', 'status', 'slug', 'showInMenu'], 'required'],
            ['name', 'string', 'max' => 255],
            ['parentId', 'exist', 'targetClass' => Category::class, 'targetAttribute' => 'id'],
            [['status', 'showInMenu'], 'boolean'],
            ['slug', SlugValidator::class],
            [
                'slug',
                'unique',
                'targetClass' => Category::class,
                'when' => function() {
                    return is_null($this->_category);
                }
            ]
        ];
    }

    protected function internalForms()
    {
        return ['translations'];
    }
}