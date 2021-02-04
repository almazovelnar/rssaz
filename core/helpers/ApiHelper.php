<?php

namespace core\helpers;

use Yii;
use yii\web\{Request, Response};
use core\entities\Customer\Website\Website;

/**
 * Class ApiHelper
 * @package core\helpers
 */
class ApiHelper
{
    public static function determineLanguage(Request $request, Website $website): string
    {
        $requestedLang = $request->get('lang', null);

        return (array_key_exists($requestedLang, Yii::$app->params['languages'])
                ? $requestedLang : ($website->getDefaultLanguage() ?? Yii::$app->params['defaultLanguage']));
    }

    public function setResponseContentType(Response $response): void
    {
        $response->format = Response::FORMAT_RAW;
        $response->getHeaders()->set('Content-Type', 'application/javascript; charset=utf-8');
    }
}