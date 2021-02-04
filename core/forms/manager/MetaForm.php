<?php

namespace core\forms\manager;

use core\entities\Meta;
use yii\base\Model;

/**
 * Class MetaForm
 * @package core\forms\manager
 */
class MetaForm extends Model
{
    /**
     * @var string
     */
    public $language;
    /**
     * @var string
     */
    public $title;
    /**
     * @var string
     */
    public $description;
    /**
     * @var string
     */
    public $keywords;

    /**
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function formName()
    {
        return parent::formName() . '_' . $this->language;
    }

    /**
     * MetaForm constructor.
     * @param string $language
     * @param Meta|null $meta
     * @param array $config
     */
    public function __construct(string $language, Meta $meta = null, array $config = [])
    {
        $this->language = $language;

        if ($meta) {
            $this->title = $meta->title;
            $this->description = $meta->description;
            $this->keywords = $meta->keywords;
        }

        parent::__construct($config);
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            ['title', 'string', 'max' => 200],
            [['description', 'keywords'], 'string']
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'title' => 'Meta başlığı',
            'keywords' => 'Meta açar sözləri',
            'description' => 'Meta təsviri',
        ];
    }
}