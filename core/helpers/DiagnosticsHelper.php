<?php

namespace core\helpers;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class DiagnosticsHelper
{
    public static function statusesList(?string $exclude = null): array
    {
        $statuses = [
            LIBXML_ERR_NONE => 'Success',
            LIBXML_ERR_WARNING => 'Warning',
            LIBXML_ERR_ERROR => 'Danger',
            LIBXML_ERR_FATAL => 'Fatal',
        ];

        if ($exclude) {
            if (($status = array_search(ucfirst($exclude), $statuses)) !== null)
                unset($statuses[$status]);
        }

        return $statuses;
    }

    public static function statusName($status)
    {
        return ArrayHelper::getValue(self::statusesList(), $status);
    }

    public static function statusLabel($status, $class = null)
    {
        switch ($status) {
            case LIBXML_ERR_NONE:
                $type = 'success';
                break;
            case LIBXML_ERR_WARNING:
                $type = 'warning';
                break;
            case LIBXML_ERR_ERROR:
                $type = 'danger';
                break;
            case LIBXML_ERR_FATAL:
                $type = 'dark';
                break;
            default:
                $type = 'secondary';
                break;
        }

        $class = ($class != null) ? $class : 'badge badge';
        return Html::tag('span', ArrayHelper::getValue(self::statusesList(), $status), [
            'class' => $class . '-' . $type
        ]);
    }
}