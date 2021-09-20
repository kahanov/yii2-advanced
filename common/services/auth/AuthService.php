<?php

namespace common\services\auth;

use yii;
use common\models\user\User;
use common\forms\auth\LoginForm;
use yii\mail\MailerInterface;

/**
 * Authorization service
 */
class AuthService
{
    private $mailer;

    /**
     * AuthService constructor.
     * @param MailerInterface $mailer
     */
    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @param LoginForm $form
     * @return User
     */
    public function auth(LoginForm $form): User
    {
        $user = $this->findByUsernameOrEmail($form->username);

        if (!$user || !$user->validatePassword($form->password)) {
            throw new \DomainException(Yii::t('user', 'Неверный логин или пароль'));
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

        return $user;
    }

    /**
     * @param $value
     * @return User|null
     */
    public function findByUsernameOrEmail($value): ?User
    {
        return User::find()->andWhere(['or', ['username' => $value], ['email' => $value]])->limit(1)->one();
    }

    /**
     * Is there a user with this service
     * @param $network
     * @param $identity
     * @return User|null
     */
    public function findByNetworkIdentity($network, $identity): ?User
    {
        return User::find()->joinWith('networks n')->andWhere(['n.network' => $network, 'n.identity' => $identity])->limit(1)->one();
    }
}
