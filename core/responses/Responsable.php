<?php

namespace core\responses;

use yii\web\Request;

/**
 * Interface Responsable
 */
interface Responsable
{
    public function toResponse(Request $request);
}