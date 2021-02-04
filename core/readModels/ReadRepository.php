<?php

namespace core\readModels;

use Yii;

abstract class ReadRepository
{
    protected $language;

    public function __construct()
    {
        $this->language = Yii::$app->language;
    }

    public function setLanguage(string $language)
    {
        if (array_key_exists($language, Yii::$app->params['languages'])) {
            $this->language = $language;
        }
        return $this;
    }
}