<?php

namespace common\services\user;

use common\models\user\User;
use Yii;

/**
 * User service
 */
class UserService
{
    /**
     * Get user
     * @param $id
     * @return User
     */
    public function get($id): User
    {
        return $this->getBy(['id' => $id]);
    }

    /**
     * @param $email
     * @return User
     */
    public function getByEmail($email): User
    {
        return $this->getBy(['email' => $email]);
    }

    /**
     * @param string $token
     * @return bool
     */
    public function existsByPasswordResetToken(string $token): bool
    {
        return (bool)User::findByPasswordResetToken($token);
    }

    /**
     * @param $token
     * @return User
     */
    public function getByPasswordResetToken($token): User
    {
        return $this->getBy(['password_reset_token' => $token]);
    }

    /**
     * @param $token
     * @return User
     */
    public function getByEmailConfirmToken($token): User
    {
        return $this->getBy(['email_confirm_token' => $token]);
    }

    /**
     * @param array $condition
     * @return User
     */
    private function getBy(array $condition): User
    {
        /** @var User $user */
        if (!$user = User::find()->andWhere($condition)->limit(1)->one()) {
            throw new \DomainException(Yii::t('common', 'Данные не найдены'));
        }

        return $user;
    }
}
