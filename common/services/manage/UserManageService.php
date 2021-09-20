<?php

namespace common\services\manage;

use Yii;
use common\services\user\UserService;
use common\models\user\User;
use backend\forms\user\UserEditForm;
use backend\forms\user\UserCreateForm;
use common\services\RoleManager;
use common\services\TransactionManager;

/**
 * User management service
 */
class UserManageService
{
    private $userService;
    private $role;
    private $transaction;

    /**
     * UserManageService constructor.
     * @param UserService $userService
     * @param RoleManager $role
     * @param TransactionManager $transaction
     */
    public function __construct(
        UserService $userService,
        RoleManager $role,
        TransactionManager $transaction
    )
    {
        $this->userService = $userService;
        $this->role = $role;
        $this->transaction = $transaction;
    }

    /**
     * @param UserCreateForm $form
     * @return User
     * @throws \Throwable
     * @throws \yii\base\Exception
     */
    public function create(UserCreateForm $form): User
    {
        $user = User::create(
            $form->username,
            $form->email,
            $form->password
        );

        $this->transaction->wrap(function () use ($user, $form) {
            if (!$user->save()) {
                throw new \RuntimeException(Yii::t('common', 'Не удалось сохранить'));
            }
            $this->role->assign($user->id, $form->role);
        });

        return $user;
    }

    /**
     * @param $id
     * @param UserEditForm $form
     * @throws \Throwable
     */
    public function edit($id, UserEditForm $form): void
    {
        $user = $this->userService->get($id);
        $user->username = $form->username;
        $user->email = $form->email;
        $user->updated_at = time();
        $this->transaction->wrap(function () use ($user, $form) {
            if (!$user->save()) {
                throw new \RuntimeException(Yii::t('common', 'Не удалось сохранить'));
            }
            $this->role->assign($user->id, $form->role);
        });
    }

    /**
     * @param $id
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function remove($id): void
    {
        $user = $this->userService->get($id);
        if (!$user->delete()) {
            throw new \RuntimeException(Yii::t('common', 'Не удалось удалить'));
        }
    }

    /**
     * @param $id
     * @param $role
     * @throws \Exception
     */
    public function assignRole($id, $role): void
    {
        $user = $this->userService->get($id);
        $this->role->assign($user->id, $role);
    }
}
