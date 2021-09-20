<?php

namespace frontend\forms\account\profile;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

class LogotypeForm extends Model
{
    /**
     * @var UploadedFile[]
     */
    public $logotype;
    public $x;
    public $y;
    public $x2;
    public $y2;
    public $w;
    public $h;

    public function formName(): string
    {
        return '';
    }

    public function rules(): array
    {
        return [
            [['logotype'], 'image',
                'maxSize' => 2097152,
                'tooBig' => Yii::t('avatar', 'Ошибка размера файла', ['size' => 2097152 / (1024 * 1024)]),
                'extensions' => 'jpeg, jpg, png, gif',
                'wrongExtension' => Yii::t('avatar', 'Ошибка расширения файла', ['formats' => 'jpeg, jpg, png, gif'])
            ],
            [['x', 'y', 'x2', 'y2', 'w', 'h'], 'number'],
        ];
    }

    public function beforeValidate(): bool
    {
        if (parent::beforeValidate()) {
            $this->logotype = UploadedFile::getInstance($this, 'logotype');
            return true;
        }
        return false;
    }
}
