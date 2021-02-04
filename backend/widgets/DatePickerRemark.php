<?php

namespace backend\widgets;

use kartik\date\DatePicker;
use yii\base\InvalidConfigException;
use yii\helpers\Html;

/**
 * Class DatePickerRemark
 * @package backend\widgets
 */
class DatePickerRemark extends DatePicker
{

    private $_container = [];

    /**
     * Initializes picker icon and remove icon
     * @param string $type the icon type 'picker' or 'remove'
     * @param string $bs3Icon the icon suffix name for Bootstrap 3 version
     * @param string $bs4Icon the icon suffix name for Bootstrap 4 version
     */
    protected function initIcon($type, $bs3Icon, $bs4Icon)
    {
        $css = $this->isBs4() ? "md-{$bs4Icon}" : "md-{$bs3Icon}";
        $icon = $type . 'Icon';
        if (!isset($this->$icon)) {
            $this->$icon = Html::tag('i', '', ['class' => $css . ' kv-dp-icon']);
        }
    }

    /**
     * Renders the date picker widget.
     * @throws InvalidConfigException
     */
    protected function renderDatePicker()
    {
        $this->initIcon('remove', 'delete', 'delete');
        parent::renderDatePicker();
    }
}