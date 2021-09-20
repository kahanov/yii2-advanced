<?php

namespace frontend\forms;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class ContactForm extends Model
{
    public $name;
    public $email;
    public $subject;
    public $body;
    public $verifyCode;
    public $check;
	
	
	/**
	 * @return array
	 */
	public function rules(): array
	{
        return [
            // name, email, subject and body are required
            [['name', 'email', 'subject', 'body', 'check'], 'required'],
            // email has to be a valid email address
            ['email', 'email'],
            [['email', 'check'], 'string', 'max' => 255],
            // verifyCode needs to be entered correctly
            ['verifyCode', 'captcha'],
        ];
    }
	
	/**
	 * @return array
	 */
	public function attributeLabels(): array
	{
		return [
			'name' => Yii::t('frontend/site', 'Ваше имя'),
			'subject' => Yii::t('frontend/site', 'Тема'),
			'body' => Yii::t('frontend/site', 'Сообщение'),
			'email' => Yii::t('frontend/site', 'E-mail'),
			'verifyCode' => Yii::t('frontend/site', 'Проверочный код'),
		];
	}
}
