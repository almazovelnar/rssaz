<?php

namespace core\helpers;

use yii\base\Application;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class CommonHelper
{
    const UPLOAD_IMAGE = 'image';
    const UPLOAD_PDF = 'pdf';

    //STATUSES
    public static function statusesList()
    {
        return ['Inactive', 'Active'];
    }

    public static function statusName($status)
    {
        return ArrayHelper::getValue(self::statusesList(), $status);
    }

    /**
     * @param array $data
     * @param string|null $label
     * @return array
     */
    public static function makeDropdown(array $data, ?string $label = null): array
    {
        $out = [];
        foreach ($data as $index => $value) {
            $out[$value->id] = ($label) ? $value->{$label} : $value->name;
        }
        return $out;
    }

    public static function statusLabel($status)
    {
        switch ($status) {
            case 0:
                $class = 'badge badge-danger';
                break;
            case 1:
                $class = 'badge badge-success';
                break;
            default:
                $class = 'badge badge-default';
                break;
        }

        return Html::tag('span', ArrayHelper::getValue(self::statusesList(), $status), [
            'class' => $class
        ]);
    }
    //STATUSES

    public static function grayLogRequisites(Application $app)
    {
        $vars = ['SERVER_NAME', 'REQUEST_URI', 'REMOTE_ADDR', 'HTTP_ORIGIN', 'HTTP_REFERER', 'HTTP_USER_AGENT', 'CONTENT_TYPE', 'HTTP_COOKIE'];
        $dump = [];
        foreach ($vars as $var) {
            if (isset($_SERVER[$var])) {
                $dump[$var] = $_SERVER[$var];
            }
        }

        if (isset($dump['REQUEST_URI'])) $dump['X_FORWARDED_FOR'] = $app->request->headers->get('X-Forwarded-For');

        return $dump;
    }
}