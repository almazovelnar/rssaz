<?php

namespace core\components\Storage;

use core\components\Storage\directories\CustomerDirectory;
use core\components\Storage\directories\PostDirectory;
use yii\base\Component;

/**
 * Class Storage
 * @package core\components\Storage
 * @property CustomerDirectory $customer
 * @property PostDirectory $post
 */
class Storage extends Component
{
    private $directories = [];

    /**
     * @param string $name (post|redaction)
     * @return mixed
     * @throws \yii\base\UnknownPropertyException
     */
    public function __get($name)
    {
        if (isset($this->directories[$name])) {
            return $this->directories[$name];
        }

        $directoryClassName = $this->getDirectoryClassName($name);
        if (class_exists($directoryClassName)) {
            $this->directories[$name] = new $directoryClassName;
            return $this->directories[$name];
        }

        return parent::__get($name);
    }

    /**
     * @param $name
     * @return string
     */
    public function getDirectoryClassName($name)
    {
        return __NAMESPACE__ . '\directories\\' . ucfirst($name) . 'Directory';
    }
}