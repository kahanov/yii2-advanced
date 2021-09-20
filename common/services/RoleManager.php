<?php

namespace common\services;

use Yii;

class RoleManager
{
    private $manager;

    const ROLE_USER = 'user';
    const ROLE_ADMIN = 'admin';

    /**
     * RoleManager constructor.
     */
    public function __construct()
    {
        $this->manager = Yii::$app->authManager;
    }

    /**
     * @param $userId
     * @param $name
     * @throws \Exception
     */
    public function assign($userId, $name): void
    {
        if (!$role = $this->manager->getRole($name)) {
            throw new \DomainException('Role "' . $name . '" does not exist.');
        }
        $this->manager->revokeAll($userId);
        $this->manager->assign($role, $userId);
    }
}
