<?php

namespace common\services\auth;

use common\services\user\UserService;
use yii;
use common\models\user\User;
use yii\helpers\ArrayHelper;
use common\services\RoleManager;
use common\services\TransactionManager;

/**
 * Authorization service via third-party services
 */
class NetworkService
{
    private $userService;
    private $authService;
    private $role;
    private $transaction;

    /**
     * NetworkService constructor.
     * @param UserService $userService
     * @param AuthService $authService
     * @param RoleManager $role
     * @param TransactionManager $transaction
     */
    public function __construct(
        UserService $userService,
        AuthService $authService,
        RoleManager $role,
        TransactionManager $transaction
    )
    {
        $this->userService = $userService;
        $this->authService = $authService;
        $this->role = $role;
        $this->transaction = $transaction;
    }

    /**
     * @param $network
     * @param array $attributes
     * @return User|null
     * @throws \Throwable
     * @throws yii\base\Exception
     */
    public function auth($network, array $attributes): ?User
    {
        $identity = ArrayHelper::getValue($attributes, 'id');
        $email = ArrayHelper::getValue($attributes, 'email');
        $email = (empty($email)) ? ArrayHelper::getValue($attributes, 'default_email') : $email;
        if (empty($email)) {
            return NULL;
        }
        if ($user = $this->authService->findByNetworkIdentity($network, $identity)) {
            return $user;
        }
        if ($user = $this->authService->findByUsernameOrEmail($email)) {
            $user->addNetwork($network, $identity);
        } else {
            $user = User::signupByNetwork($network, $identity, $attributes);
        }
        $this->transaction->wrap(function () use ($user) {
            if (!$user->save()) {
                throw new \RuntimeException(Yii::t('common', 'Не удалось сохранить'));
            }
            $this->role->assign($user->id, $this->role::ROLE_USER);
        });
        return $user;
    }

    /**
     * Network is already attached
     * @param $id
     * @param $network
     * @param $identity
     */
    public function attach($id, $network, $identity): void
    {
        if ($this->authService->findByNetworkIdentity($network, $identity)) {
            throw new \DomainException(Yii::t('user', 'Данный аккаунт уже используется'));
        }

        $user = $this->userService->get($id);
        $user->attachNetwork($network, $identity);
        if (!$user->save()) {
            throw new \RuntimeException(Yii::t('common', 'Не удалось сохранить'));
        }
    }
}
