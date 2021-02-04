<?php

namespace cabinet\widgets\Guides;

use yii\base\Widget;
use core\entities\Page\Page;
use core\readModels\PageReadRepository;

class Guides extends Widget
{
    private $pageReadRepository;

    public function __construct($config = [], PageReadRepository $pageReadRepository)
    {
        parent::__construct($config);

        $this->pageReadRepository = $pageReadRepository;
    }

    public function run()
    {
        return $this->render('index', [
            'pages' => $this->pageReadRepository->getPages(Page::TYPE_CABINET, 5),
        ]);
    }
}