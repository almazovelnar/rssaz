<?php

namespace backend\widgets;

use yii\grid\GridView;

/**
 * Class GridViewRemark
 * @package backend\widgets
 */
class GridViewRemark extends GridView
{

    /**
     * @var array
     */
    public $options = ['class' => 'gridview table-responsive'];
    /**
     * @var array
     */
    public $tableOptions = ['class' => 'table table-striped'];

    /**
     * @var array
     */
    public $pager = [
        'options'                       => ['class' => 'pagination float-right'],
        'pageCssClass'                  => 'page-item',
        'linkOptions'                   => ['class' => 'page-link'],
        'prevPageLabel'                 => '«',
        'nextPageLabel'                 => '»',
        'disabledPageCssClass'          => 'page-item disabled',
        'disabledListItemSubTagOptions' => [
            'tag'   => 'a',
            'class' => 'page-link'
        ]
    ];

}