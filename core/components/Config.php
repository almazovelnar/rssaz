<?php

namespace core\components;

use Yii;
use DomainException;
use yii\base\Component;
use core\entities\Config as Entity;

/**
 * Class Config
 * @package core\components
 */
class Config extends Component
{
    public $cache = false;
    protected $data = [];

    public function init():void
    {
        $items = $this->getItems();
        foreach ($items as $item) {
            /** @var $item Entity */
            if (! empty($item['param'])) {
                $value = $item['value'] === '' ? $item['default'] : $item['value'];
                settype($value, $item['type']);
                $this->data[$item['param']] = $value;
            }
        }

        parent::init();
    }

    public function get($key)
    {
        if (! array_key_exists($key, $this->data)) {
            throw new DomainException('Undefined parameter: ' . $key);
        }

        return $this->data[$key];
    }

    public function set($key, $value)
    {
        if (! ($model = Entity::findOne(['param' => $key]))) {
            throw new DomainException('Undefined parameter ' . $key);
        }

        $this->data[$key] = $value;
        $model->value = $value;
        $model->save();
    }

    public function getItems()
    {
        $db = Yii::$app->db;
        $cache = Yii::$app->cache;

        return $cache->getOrSet('configs', fn() => $db->createCommand('SELECT * FROM config')->queryAll());
    }
}