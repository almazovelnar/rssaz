<?php

namespace core\entities;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "config".
 *
 * @property int $id
 * @property string $param
 * @property string $value
 * @property string $default
 * @property string $label
 * @property string $type
 */
class Config extends ActiveRecord
{
    public const TYPE_SRTRING = 'string';
    public const TYPE_BOOL = 'bool';
    public const TYPE_INT = 'int';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'config';
    }

    /**
     * @return array
     */
    public static function getTypes(): array
    {
        return [
            self::TYPE_SRTRING => 'String',
            self::TYPE_BOOL => 'Boolean',
            self::TYPE_INT  => 'Integer',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['param', 'type', 'value'], 'required'],
            [['value', 'default'], 'string'],
            [['param', 'label'], 'string', 'max' => 255],
            [['type'], 'string', 'max' => 50],
            [['param'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'      => 'ID',
            'param'   => 'Key',
            'value'   => 'Value',
            'default' => 'Default',
            'label'   => 'Label',
            'type'    => 'Type',
        ];
    }
}
