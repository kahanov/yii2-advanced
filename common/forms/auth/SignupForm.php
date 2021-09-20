<?php

namespace common\forms\auth;

use Yii;
use yii\base\Model;
use common\models\user\User;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $verifyCode;
    public $check;

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['username', 'email', 'password', 'check'], 'required'],
            ['username', 'trim'],
            ['username', 'unique', 'targetClass' => User::class, 'message' => Yii::t('common/login', 'Этот логин уже используется')],
            ['username', 'string', 'min' => 2, 'max' => 255],
            ['email', 'trim'],
            ['email', 'email'],
            [['email', 'check'], 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => User::class, 'message' => Yii::t('common/login', 'Этот email уже используется')],
            ['password', 'string', 'min' => 6],
            ['verifyCode', 'captcha', 'on' => 'signup'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels(): array
    {
        return [
            'username' => Yii::t('common/login', 'Логин'),
            'password' => Yii::t('common/login', 'Пароль'),
            'verifyCode' => Yii::t('common/login', 'Проверочный код'),
            'email' => Yii::t('frontend/site', 'E-mail'),
        ];
    }
}

