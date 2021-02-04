<?php

namespace core\helpers;

use Yii;
use yii\helpers\{ArrayHelper, Html, Url};
use core\entities\Customer\Website\Rss;
use core\entities\Customer\Website\Website;

class WebsiteHelper
{
    public static function rssCanBeRefreshed(Website $website, $language)
    {
        return Rss::find()->where(['website_id' => $website->id, 'lang' => $language])->exists();
    }

    public static function frequencyList(): array
    {
        return [];
    }

    public static function frequencyName($frequency)
    {
        return ArrayHelper::getValue(self::frequencyList(), $frequency);
    }

    public static function frequencyLabel($frequency)
    {
        return Html::tag('span', ArrayHelper::getValue(self::frequencyList(), $frequency), [
            'class' => self::getLabelClass('warning')
        ]);
    }

    public static function statusesList()
    {
        return [
            Website::STATUS_ACTIVE => Yii::t('website', 'active'),
            Website::STATUS_BLOCKED => Yii::t('website', 'blocked'),
            Website::STATUS_WAITING => Yii::t('website', 'wait'),
        ];
    }

    public static function statusName($status)
    {
        return ArrayHelper::getValue(self::statusesList(), $status);
    }

    public static function statusLabel($status)
    {
        switch ($status) {
            case Website::STATUS_WAITING:
                $class = 'warning';
                break;
            case Website::STATUS_ACTIVE:
                $class = 'success';
                break;
            case Website::STATUS_BLOCKED:
                $class = 'danger';
                break;
            default:
                $class = 'default';
                break;
        }

        return Html::tag('span', ArrayHelper::getValue(self::statusesList(), $status), [
            'class' => self::getLabelClass($class)
        ]);
    }

    private static function getLabelClass($class)
    {
        return (isset(Yii::$app->params['bsVersion']) && Yii::$app->params['bsVersion'] == '4.x') ? 'badge badge-' . $class : 'badge badge-' . $class;
    }

    /**
     * @return array
     */
    public static function otherLanguages(): array
    {
        return array_filter(Yii::$app->params['languages'], function ($lang) {
            return Yii::$app->language !== $lang;
        }, ARRAY_FILTER_USE_KEY);
    }

    // Temporary solution
    public static function generateRedirectUrl(string $locale, array $params)
    {
        return self::makeLanguageUrl($locale)
            . '/redirect/' . $params['id'] . '?sid=' . $params['sid'];
    }

    /**
     * @param string $locale
     * @return string
     */
    public static function makeLanguageUrl(string $locale): string
    {
        preg_match("/^(?<protocol>(http|https):\/\/)(((?<subdomain>[a-z]+)\.)*)((.*\.)*(?<domain>.+\.[a-z]+))$/", Url::base(true), $matches);

        return $locale === Yii::$app->params['defaultLanguage']
            ? $matches['protocol'] . $matches['domain']
            : $matches['protocol'] . $locale . '.' . $matches['domain'];
    }

}