<?php

namespace core\entities\Customer\Review;

use yii\db\ActiveQuery;
use core\entities\Customer\Website\Website;
use core\forms\manager\CustomerReview\TranslationForm;
use dosamigos\translateable\TranslateableBehavior;

/**
 * This is the model class for table "customer_reviews".
 *
 * @property int $id
 * @property int $status
 * @property int $website_id
 * @property string $created_at
 * @property string $language
 * @property string $review
 *
 * @property Translation $multilingual
 * @property TranslationForm[] $translations
 * @mixin TranslateableBehavior
 * @method Translation getTranslation(string $code)
 */
class Review extends \yii\db\ActiveRecord
{
    public static function tableName(): string
    {
        return 'customer_reviews';
    }

    public function behaviors(): array
    {
        return [
            'translation' => [
                'class' => TranslateableBehavior::class,
                'translationAttributes' => ['review']
            ],
        ];
    }

    /**
     * @param int $website_id
     * @param bool $status
     * @return Review
     */
    public static function create(int $website_id, bool $status): self
    {
        $page = new self;
        $page->website_id = $website_id;
        $page->status = $status;
        return $page;
    }

    /**
     * @param int $website_id
     * @param bool $status
     */
    public function edit(int $website_id, bool $status): void
    {
        $this->status = $status;
        $this->website_id = $website_id;
    }

    /**
     * @param string $language
     * @param string $review
     */
    public function setVersion(
        string $language,
        string $review
    ): void
    {
        $this->language = $language;
        $this->review = $review;
    }

    public function getWebsite()
    {
        return $this->hasOne(Website::class, ['website_id' => 'id']);
    }

    public function getTranslations(): ActiveQuery
    {
        return $this->hasMany(Translation::class, ['review_id' => 'id']);
    }

    public function getMultilingual(): ActiveQuery
    {
        return $this->hasOne(Translation::class, ['review_id' => 'id']);
    }
}
