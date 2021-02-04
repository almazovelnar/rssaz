<?php

namespace core\components\Storage\directories;

class CustomerDirectory extends Directory
{
    public function directory(): string
    {
        return 'customer';
    }
}