<?php

namespace backend\modules\website\grid;

use backend\widgets\ActionColumnRemark;
use core\entities\Customer\Website\Website;
use yii\grid\ActionColumn;

class WebsiteActionColumn extends ActionColumnRemark
{
    /**
     * @var string
     */
    public $template = '{activate} {block} {view} {update} {delete}';

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->visibleButtons = [
            'activate' => function (Website $website) {
                return !$website->isActive();
            },
            'block' => function (Website $website) {
                return !$website->isBlocked();
            },
        ];

        parent::init();
    }

    /**
     * @inheritdoc
     */
    protected function initDefaultButtons()
    {
        $this->initDefaultButton('activate', 'thumb-up', ['data-method' => 'post']);
        $this->initDefaultButton('block', 'thumb-down', ['data-method' => 'post']);

        parent::initDefaultButtons();
    }
}