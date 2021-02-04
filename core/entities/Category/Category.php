<?php

namespace core\entities\Category;

use core\entities\Customer\Website\Post;
use core\entities\Meta;
use core\entities\queries\CategoryQuery;
use core\forms\manager\Category\TranslationForm;
use core\behaviors\TranslateableBehavior;
use paulzi\nestedsets\NestedSetsBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Class Category
 * @package core\entities
 * @property int $id
 * @property string $name
 * @property int $lft
 * @property int $rgt
 * @property int $depth
 * @property bool $status
 * @property bool $show_in_menu
 * @property string $created_at
 * @property string $updated_at
 *
 * @property string $language
 * @property string $title
 * @property string $slug
 * @property Meta $meta
 *
 * @property Translation $multilingual
 * @property TranslationForm[] $translations
 * @property Category $parent
 * @property Post[] $posts
 *
 * @mixin TranslateableBehavior
 * @mixin NestedSetsBehavior
 */
class Category extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%categories}}';
    }

    /**
     * @return array
     */
    public function behaviors(): array
    {
        return [
            'translation' => [ // name it the way you want
                'class' => TranslateableBehavior::class,
                'translationAttributes' => ['title', 'meta']
            ],
            'nestedSet' => [
                'class' => NestedSetsBehavior::class,
            ],
        ];
    }

    /**
     * @return array
     */
    public function transactions()
    {
        return [self::SCENARIO_DEFAULT => self::OP_ALL];
    }

    /**
     * @return CategoryQuery|ActiveQuery
     */
    public static function find()
    {
        return new CategoryQuery(get_called_class());
    }

    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param $name
     * @param $slug
     * @param $status
     * @param $showInMenu
     * @return Category
     */
    public static function create($name, $slug, $status, $showInMenu): self
    {
        $category = new self();
        $category->name = $name;
        $category->slug = $slug;
        $category->status = $status;
        $category->show_in_menu = $showInMenu;
        return $category;
    }

    /**
     * @param $name
     * @param $slug
     * @param $status
     * @param $showInMenu
     */
    public function edit($name, $slug, $status, $showInMenu): void
    {
        $this->name = $name;
        $this->slug = $slug;
        $this->status = $status;
        $this->show_in_menu = $showInMenu;
    }

    /**
     * @param $language
     * @param $title
     * @param Meta $meta
     */
    public function setVersion($language, $title, Meta $meta): void
    {
        $this->language = $language;
        $this->title = $title;
        $this->meta = $meta;
    }

    /**
     * @param int $id
     * @return bool
     */
    public function parentIdIsEqualTo(int $id)
    {
        return $this->parent->id == $id;
    }

    /**
     * @return ActiveQuery
     */
    public function getTranslations(): ActiveQuery
    {
        return $this->hasMany(Translation::class, ['category_id' => 'id']);
    }

    public function getMultilingual()
    {
        return $this->hasOne(Translation::class, ['category_id' => 'id']);
    }

    public function getPosts()
    {
        return $this->hasMany(Post::class, ['category_id' => 'id']);
    }
}