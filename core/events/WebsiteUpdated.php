<?php

namespace core\events;

use core\entities\Customer\Website\Website;

/**
 * Class WebsiteUpdated
 * @package core\events
 */
class WebsiteUpdated
{
    private Website $website;

    public function __construct(Website $website)
    {
        $this->website = $website;
    }

    public function getWebsite(): Website
    {
        return $this->website;
    }
}
