<?php

namespace core\entities\Page;

use core\entities\Meta;
use core\forms\manager\Page\TranslationForm;
use yii\db\ActiveQuery;
use dosamigos\translateable\TranslateableBehavior;

/**
 * This is the model class for table "pages".
 *
 * @property int $id
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 * @property string $language
 * @property string $title
 * @property string $slug
 * @property string $description
 * @property bool $show
 * @property string $type
 * @property Meta $meta
 *
 * @property Translation $multilingual
 * @property TranslationForm[] $translations
 * @mixin TranslateableBehavior
 * @method Translation getTranslation(string $code)
 */
class Page extends \yii\db\ActiveRecord
{
    const TYPE_FRONTEND = 'frontend';
    const TYPE_CABINET = 'cabinet';

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'pages';
    }

    /**
     * @return array
     */
    public function behaviors(): array
    {
        return [
            'translation' => [ // name it the way you want
                'class' => TranslateableBehavior::class,
                // 'relation' => 'translations',
                // 'languageField' => 'language',
                'translationAttributes' => ['title', 'description', 'meta']
            ],

        ];
    }

    /**
     * @param string $slug
     * @param bool $status
     * @param bool $show
     * @param string $type
     * @return Page
     */
    public static function create(string $slug, bool $status, bool $show, string $type): self
    {
        $page = new self;
        $page->slug = $slug;
        $page->status = $status;
        $page->show = $show;
        $page->type = $type;
        return $page;
    }

    /**
     * @param string $slug
     * @param bool $status
     * @param bool $show
     * @param string $type
     */
    public function edit(string $slug, bool $status, bool $show, string $type): void
    {
        $this->slug = $slug;
        $this->show = $show;
        $this->status = $status;
        $this->type = $type;
    }

    /**
     * @param $language
     * @param $title
     * @param $description
     * @param Meta $meta
     */
    public function setVersion(
        string $language,
        string $title,
        ?string $description,
        Meta $meta
    ): void
    {
        $this->language = $language;
        $this->title = $title;
        $this->description = $description;
        $this->meta = $meta;
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'status' => 'Status',
            'created_at' => 'Yaradılma tarixi',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getTranslations(): ActiveQuery
    {
        return $this->hasMany(Translation::class, ['page_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getMultilingual(): ActiveQuery
    {
        return $this->hasOne(Translation::class, ['page_id' => 'id']);
    }
}
