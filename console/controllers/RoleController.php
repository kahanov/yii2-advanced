<?php

namespace console\controllers;

use common\models\user\User;
use common\services\manage\UserManageService;
use Yii;
use yii\console\Controller;
use yii\console\Exception;
use yii\helpers\ArrayHelper;

/**
 * Interactive console roles manager
 */
class RoleController extends Controller
{
    private $service;

    /**
     * RoleController constructor.
     * @param $id
     * @param $module
     * @param UserManageService $service
     * @param array $config
     */
    public function __construct($id, $module, UserManageService $service, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
    }

    /**
     * Adds role to user
     * @throws Exception
     */
    public function actionAssign(): void
    {
        $username = $this->prompt('Username:', ['required' => true]);
        $user = $this->findModel($username);
        $role = $this->select('Role:', ArrayHelper::map(Yii::$app->authManager->getRoles(), 'name', 'description'));
        $this->service->assignRole($user->id, $role);
        $this->stdout('Done!' . PHP_EOL);
    }

    /**
     * @param $username
     * @return User
     * @throws Exception
     */
    private function findModel($username): User
    {
        if (!$model = User::findOne(['username' => $username])) {
            throw new Exception('User is not found');
        }
        return $model;
    }
}
