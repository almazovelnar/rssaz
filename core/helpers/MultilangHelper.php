<?php

namespace core\helpers;

use yii;

/**
 * Class MultilangHelper
 * @package core\helpers
 */
class MultilangHelper
{
    /**
     * @return bool
     */
    public static function enabled()
    {
        return count(Yii::$app->params['languages']) > 1;
    }

    /**
     * @return array
     */
    public static function suffixList()
    {
        $list = array();
        $enabled = self::enabled();

        foreach (Yii::$app->params['languages'] as $lang => $name)
        {
            if ($lang === Yii::$app->params['defaultLanguage']) {
                $suffix = '';
                $list[$suffix] = $enabled ? $name : '';
            } else {
                $suffix = '_' . $lang;
                $list[$suffix] = $name;
            }
        }

        return $list;
    }

    /**
     * @param $url
     * @param string $language
     * @return string
     */
    public static function addCustomLanguageToUrl($url, string $language): string
    {
        if (self::enabled())
        {
            $domains = explode('/', ltrim($url, '/'));
            $isHasLang = in_array($domains[0], array_keys(Yii::$app->params['languages']));
            $isDefaultLang = $language == Yii::$app->params['defaultLanguage'];

            if ($isHasLang && $isDefaultLang)
                array_shift($domains);

            if (!$isHasLang && !$isDefaultLang)
                array_unshift($domains, $language);

            $url = '/' . implode('/', $domains);
        }

        return $url;
    }

    /**
     * @param string $hostInfo
     */
    public static function processLangByHost(string $hostInfo): void
    {
        if (
            self::enabled()
            && preg_match("/^(?<protocol>(http|https):\/\/)(((?<languageCode>[a-z]{2})\.)*)((.*\.)*(?<domain>.+\.[a-z]+))$/", $hostInfo, $matches)
            && in_array($matches['languageCode'], array_keys(Yii::$app->params['languages']))
        ) {
            Yii::$app->language = $matches['languageCode'];
        }
    }
}