<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%params}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $value
 * @property string $desc
 * @property string $type
 */
class Config extends ActiveRecord
{
    static $params = false;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'config';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 255],
            [['value'], 'string'],
            [['name'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => 'Параметр',
            'value' => 'Значение',
            'desc' => 'Описание',
            'type' => 'Тип',
        ];
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * @param $name
     * @return mixed|null
     */
    static function getParam($name)
    {
        if (!self::$params) {
            $t_param = Config::find()->asArray()->all();
            self::$params = [];
            foreach ($t_param as $i) {
                self::$params[$i['name']] = $i;
            }
        }
        return (isset(self::$params[$name])) ? self::$params[$name] : null;
    }
}
