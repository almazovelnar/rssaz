<?php

namespace core\behaviors;

use yii\base\Behavior;
use yii\base\Event;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use core\entities\Meta;

/**
 * Class MetaBehavior
 * @package core\behaviors
 */
class MetaBehavior extends Behavior
{
    /**
     * @var string
     */
    public $attribute = 'meta';
    /**
     * @var string
     */
    public $jsonAttribute = 'meta_json';

    /**
     * @return array
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_FIND => 'onAfterFind',
            ActiveRecord::EVENT_BEFORE_INSERT => 'onBeforeSave',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'onBeforeSave',
        ];
    }

    /**
     * @param Event $event
     */
    public function onAfterFind(Event $event)
    {
        $entity = $event->sender;
        $meta = Json::decode($entity->getAttribute($this->jsonAttribute));
        $entity->{$this->attribute} = new Meta(
            ArrayHelper::getValue($meta, 'title'),
            ArrayHelper::getValue($meta, 'description'),
            ArrayHelper::getValue($meta, 'keywords')
        );
    }

    /**
     * @param Event $event
     */
    public function onBeforeSave(Event $event)
    {
        $entity = $event->sender;

        $entity->setAttribute($this->jsonAttribute, Json::encode([
            'title' => $entity->{$this->attribute}->title,
            'description' => $entity->{$this->attribute}->description,
            'keywords' => $entity->{$this->attribute}->keywords
        ]));
    }
}