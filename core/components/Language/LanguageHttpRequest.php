<?php

namespace core\components\Language;

use core\helpers\MultilangHelper;
use yii\web\Request;

/**
 * Class LanguageHttpRequest
 * @package core\components\Language
 */
class LanguageHttpRequest extends Request
{
    /**
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function getUrl(): string
    {
        MultilangHelper::processLangByHost($this->hostInfo);

        return parent::getUrl();
    }

}