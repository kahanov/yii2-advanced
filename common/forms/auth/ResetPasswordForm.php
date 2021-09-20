<?php

namespace common\forms\auth;

use Yii;
use yii\base\Model;

/**
 * Password reset form
 */
class ResetPasswordForm extends Model
{
    public $password;

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels(): array
	{
		return [
			'password' => Yii::t('common/login', 'Пароль'),
		];
	}
}

