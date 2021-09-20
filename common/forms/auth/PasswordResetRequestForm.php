<?php

namespace common\forms\auth;

use Yii;
use yii\base\Model;
use common\models\user\User;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model
{
    public $email;


    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => User::class,
                //'filter' => ['status' => User::STATUS_ACTIVE],
                'message' => Yii::t('common/login', 'Такой email не зарегистрирован')
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels(): array
    {
        return [
            'email' => Yii::t('frontend/site', 'E-mail'),
        ];
    }
}
