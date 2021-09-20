<?php

namespace frontend\forms;

use common\models\user\User;
use Yii;
use yii\base\Model;
use common\components\phone\PhoneInputValidator;

class MessageForm extends Model
{
    public $ad_id;
    public $message;
    public $name;
    public $email;
    public $phone;
    public $check;
    public $company;

    /**
     * MessageForm constructor.
     * @param array $config
     */
    public function __construct($config = [])
    {
        if (!Yii::$app->user->isGuest) {
            /** @var User $user */
            $user = Yii::$app->user->identity;
            $lastName = (!empty($user->last_name)) ? $user->last_name : NULL;
            $firstName = (!empty($user->first_name)) ? $user->first_name : NULL;
            $userName = ($lastName && $firstName) ? $firstName . ' ' . $lastName : $user->username;

            $this->name = $userName;
            $this->email = $user->email;
        }
        parent::__construct($config);
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['message', 'name', 'email'], 'required'],
            [['company'], 'required', 'on' => 'company'],
            ['email', 'email'],
            [['company'], 'integer'],
            [['name', 'check'], 'string'],
            [['message'], 'string', 'max' => 2000],
            [['phone'], PhoneInputValidator::class],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels(): array
    {
        return [
            'name' => Yii::t('common', 'Ваше имя'),
            'message' => Yii::t('common', 'Сообщение'),
            'phone' => Yii::t('common', 'Телефон'),
        ];
    }
}
