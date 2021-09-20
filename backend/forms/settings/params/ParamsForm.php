<?php

namespace backend\forms\settings\params;

use common\models\Config;
use yii\base\Model;
use Yii;

class ParamsForm extends Model
{
    public $name;
    public $value;
    public $desc;
    public $type = 'string';

    private $_param;

    public function __construct(Config $param = null, $config = [])
    {
        if ($param) {
            $this->name = $param->name;
            $this->value = $param->value;
            $this->desc = $param->desc;
            $this->type = $param->type;

            $this->_param = $param;
        }
        parent::__construct($config);
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['name', 'value', 'type'], 'required'],
            [['name', 'type'], 'string', 'max' => 255],
            [['value'], 'string'],
            [['desc'], 'string', 'max' => 10000],
            [['name'], 'unique', 'targetClass' => Config::class, 'filter' => $this->_param ? ['<>', 'id', $this->_param->id] : null],
            [['name', 'type'], 'match', 'pattern' => '/^[\w-]+$/',
                'message' => Yii::t('common', 'Поле может содержать только латинские буквы, цифры, и знаки "_", "-"')
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Параметр',
            'value' => 'Значение',
            'desc' => 'Описание',
            'type' => 'Тип',
        ];
    }
}
