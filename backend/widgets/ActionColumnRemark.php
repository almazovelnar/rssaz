<?php

namespace backend\widgets;

use Yii;
use yii\helpers\Html;

/**
 * Class ActionColumnRemark
 * @package backend\widgets
 */
class ActionColumnRemark extends \yii\grid\ActionColumn
{

    /**
     * Initializes the default button rendering callbacks.
     */
    protected function initDefaultButtons()
    {
        $this->initDefaultButton('view', 'eye');
        $this->initDefaultButton('update', 'edit');

        if (Yii::$app->user->can('deleteRecord')){
            $this->initDefaultButton('delete', 'delete', [
                'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                'data-method' => 'post',
            ]);
        }
    }

    /**
     * @param string $name
     * @param string $iconName
     * @param array $additionalOptions
     */
    protected function initDefaultButton($name, $iconName, $additionalOptions = [])
    {
        if (!isset($this->buttons[$name]) && strpos($this->template, '{' . $name . '}') !== false) {
            $this->buttons[$name] = function ($url, $model, $key) use ($name, $iconName, $additionalOptions) {
                switch ($name) {
                    case 'view':
                        $title = Yii::t('yii', 'View');
                        break;
                    case 'update':
                        $title = Yii::t('yii', 'Update');
                        break;
                    case 'delete':
                        $title = Yii::t('yii', 'Delete');
                        break;
                    default:
                        $title = ucfirst($name);
                }
                $options = array_merge([
                    'title'      => $title,
                    'aria-label' => $title,
                    'data-pjax'  => '0',
                ], $additionalOptions, $this->buttonOptions);
                $icon    = Html::tag('span', '', ['class' => "md-$iconName"]);
                return Html::a($icon, $url, $options);
            };
        }
    }
}