<?php

namespace frontend\helpers;

class WebsiteHelper
{
    public static function getIcon(string $websiteName): string
    {
        return '/images/favs/' . strtolower($websiteName) . '.ico';
    }
}