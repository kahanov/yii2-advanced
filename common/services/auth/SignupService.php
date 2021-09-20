<?php

namespace common\services\auth;

use common\services\RoleManager;
use common\services\TransactionManager;
use common\services\user\UserService;
use Yii;
use common\models\user\User;
use common\forms\auth\SignupForm;
use yii\mail\MailerInterface;

/**
 * Registration service
 */
class SignupService
{
	private $userService;
	private $mailer;
	private $role;
	private $transaction;

    /**
     * SignupService constructor.
     * @param UserService $userService
     * @param MailerInterface $mailer
     * @param RoleManager $role
     * @param TransactionManager $transaction
     */
    public function __construct(
        UserService $userService,
		MailerInterface $mailer,
		RoleManager $role,
		TransactionManager $transaction
	)
	{
        $this->userService = $userService;
		$this->mailer = $mailer;
		$this->role = $role;
		$this->transaction = $transaction;
	}
	
	/**
	 * @param SignupForm $form
	 * @throws \Throwable
	 * @throws \yii\base\Exception
	 */
	public function signup(SignupForm $form): void
	{
		$user = User::requestSignup(
			$form->username,
			$form->email,
			$form->password
		);
		
		$this->transaction->wrap(function () use ($user) {
            if (!$user->save()) {
                throw new \RuntimeException(Yii::t('common', 'Не удалось сохранить'));
            }
			$this->role->assign($user->id, $this->role::ROLE_USER);
		});
		
		$sent = $this
			->mailer
			->compose(
				['html' => 'auth/signup/confirm-html', 'text' => 'auth/signup/confirm-text'],
				['user' => $user]
			)
			->setTo($form->email)
			->setSubject(Yii::t('user', 'Подтверждение регистрации') . ' ' . Yii::$app->name)
			->send();
		
		if (!$sent) {
			throw new \RuntimeException(Yii::t('common', 'Ошибка отправки сообщения'));
		}
	}
	
	/**
	 * @param $token
	 */
	public function confirm($token): void
	{
		if (empty($token)) {
			throw new \DomainException(Yii::t('user', 'Токен не может быть пустым'));
		}
		
		$user = $this->userService->getByEmailConfirmToken($token);
		$user->confirmSignup();
        if (!$user->save()) {
            throw new \RuntimeException(Yii::t('common', 'Не удалось сохранить'));
        }
	}
}
