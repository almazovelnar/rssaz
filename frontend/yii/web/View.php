<?php

namespace frontend\yii\web;

use Yii;
use yii\helpers\Url;

/**
 * Class View
 * @package frontend\yii\web
 */
class View extends \yii\web\View
{
    /**
     * @param int|null $page
     * @param array $additionalParams
     * @param bool $hasNextPage
     */
    public function registerPaginationTags(?int $page = null, array $additionalParams = [], bool $hasNextPage = true)
    {
        $route = '/' . Yii::$app->controller->route;

        if (empty($page)) {
            $page = (int)Yii::$app->request->get('page', 1);
        }

        $prevPage = $page - 1;
        $nextPage = $page + 1;

        $routeParams = [
            $route
        ];

        if (!empty($additionalParams)) {
            $routeParams += $additionalParams;
        }

        if ($prevPage > 0) {
            $this->registerLinkTag(['rel' => 'prev', 'href' => Url::to(array_merge($routeParams, ['page' => $prevPage]), true)]);
        }
        if ($hasNextPage && $nextPage) {
            $this->registerLinkTag(['rel' => 'next', 'href' => Url::to(array_merge($routeParams, ['page' => $nextPage]), true)]);
        }
    }
}