<?php

namespace common\services\auth;

use common\services\user\UserService;
use Yii;
use common\forms\auth\PasswordResetRequestForm;
use common\forms\auth\ResetPasswordForm;
use yii\mail\MailerInterface;

/**
 * The service request processing for the password recovery
 */
class PasswordResetService
{
    private $userService;
    private $mailer;

    /**
     * PasswordResetService constructor.
     * @param UserService $userService
     * @param MailerInterface $mailer
     */
    public function __construct(UserService $userService, MailerInterface $mailer)
    {
        $this->userService = $userService;
        $this->mailer = $mailer;
    }

    /**
     * Request processing
     * @param PasswordResetRequestForm $form
     * @throws \yii\base\Exception
     */
    public function request(PasswordResetRequestForm $form): void
    {
        $user = $this->userService->getByEmail($form->email);

        if (!$user) {
            throw new \DomainException(Yii::t('user', 'Пользователь не найден'));
        }
        if (!$user->isActive()) {
            $this
                ->mailer
                ->compose(
                    ['html' => 'auth/signup/confirm-html', 'text' => 'auth/signup/confirm-text'],
                    ['user' => $user]
                )
                ->setTo($user->email)
                ->setSubject(Yii::t('user', 'Подтверждение регистрации') . ' ' . Yii::$app->name)
                ->send();
            throw new \DomainException(Yii::t('user', 'Вам необходимо подтвердить свой E-mail. Мы отправили вам повторное сообщение.'));
        }
        $user->requestPasswordReset();
        if (!$user->save()) {
            throw new \RuntimeException(Yii::t('common', 'Не удалось сохранить'));
        }

        $sent = $this
            ->mailer
            ->compose(
                ['html' => 'auth/reset/confirm-html', 'text' => 'auth/reset/confirm-text'],
                ['user' => $user]
            )
            ->setTo($user->email)
            ->setSubject(Yii::t('user', 'Запрос сброса пароля') . ' ' . Yii::$app->name)
            ->send();

        if (!$sent) {
            throw new \RuntimeException(Yii::t('common', 'Ошибка отправки сообщения'));
        }
    }

    /**
     * Validate token
     * @param $token
     */
    public function validateToken($token): void
    {
        if (empty($token) || !is_string($token)) {
            throw new \DomainException(Yii::t('user', 'Токен не может быть пустым'));
        }

        if (!$this->userService->existsByPasswordResetToken($token)) {
            throw new \DomainException(Yii::t('user', 'Токен неверный'));
        }
    }

    /**
     * Reset password
     * @param string $token
     * @param ResetPasswordForm $form
     * @throws \yii\base\Exception
     */
    public function reset(string $token, ResetPasswordForm $form): void
    {
        $user = $this->userService->getByPasswordResetToken($token);
        $user->resetPassword($form->password);
        if (!$user->save()) {
            throw new \RuntimeException(Yii::t('common', 'Не удалось сохранить'));
        }
    }
}
