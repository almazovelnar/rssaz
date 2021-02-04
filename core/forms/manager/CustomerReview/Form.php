<?php

namespace core\forms\manager\CustomerReview;

use Yii;
use core\forms\CompositeForm;
use core\entities\Customer\Review\Review;

/**
 * Class Form
 * @package core\forms\manager\CustomerReview
 *
 * @property TranslationForm[] $translations
 */
class Form extends CompositeForm
{
    public ?int $status = null;
    public ?int $websiteId = null;

    private Review $_review;

    /**
     * Form constructor.
     * @param Review|null $review
     * @param array $config
     */
    public function __construct(Review $review = null, array $config = [])
    {
        $translations = [];

        if ($review) {
            $this->websiteId = $review->website_id;
            $this->status = $review->status;
            $this->_review = $review;
            foreach (Yii::$app->params['languages'] as $code => $label) {
                $translations[] = new TranslationForm($code, $review->getTranslation($code));
            }
        } else {
            foreach (Yii::$app->params['languages'] as $code => $label) {
                $translations[] = new TranslationForm($code);
            }
        }

        $this->translations = $translations;

        parent::__construct($config);
    }

    protected function internalForms(): array
    {
        return ['translations'];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'status' => 'Status',
            'websiteId' => 'Website',
        ];
    }

    public function rules(): array
    {
        return [
            [['status', 'websiteId'], 'required'],
            [['status', 'websiteId'], 'integer'],
        ];
    }
}