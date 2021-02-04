<?php

namespace core\responses;

use Yii;

/**
 * Class ResponseDispatcher
 * @package core\responses
 */
class ResponseDispatcher
{
    public function dispatch(Responsable $responsable)
    {
        return $responsable->toResponse(Yii::$app->request);
    }
}