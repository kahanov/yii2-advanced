<?php

namespace common\helpers;

use common\services\RoleManager;
use Yii;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use common\models\user\User;
use yii\rbac\Item;

class UserHelper
{
    /**
     * @return array
     */
    public static function rolesList(): array
    {
        return ArrayHelper::map(\Yii::$app->authManager->getRoles(), 'name', 'description');
    }

    /**
     * @param Item $role
     * @return string
     * @throws \Exception
     */
    public static function roleLabel(Item $role): string
    {
        switch ($role->name) {
            case RoleManager::ROLE_ADMIN:
                $class = 'label label-danger';
                break;
            case RoleManager::ROLE_USER:
                $class = 'label label-default';
                break;
            default:
                $class = 'label label-default';
        }
        return Html::tag('span', ArrayHelper::getValue(self::rolesList(), $role->name), [
            'class' => $class,
        ]);
    }

    /**
     * @return array
     */
    public static function statusList(): array
    {
        return [
            User::STATUS_WAIT => Yii::t('backend/user', 'Ожидание'),
            User::STATUS_ACTIVE => Yii::t('backend/user', 'Активный'),
        ];
    }

    /**
     * @param $status
     * @return string
     * @throws \Exception
     */
    public static function statusName($status): string
    {
        return ArrayHelper::getValue(self::statusList(), $status);
    }

    /**
     * @param $status
     * @return string
     * @throws \Exception
     */
    public static function statusLabel($status): string
    {
        switch ($status) {
            case User::STATUS_WAIT:
                $class = 'label label-default';
                break;
            case User::STATUS_ACTIVE:
                $class = 'label label-success';
                break;
            default:
                $class = 'label label-default';
        }
        return Html::tag('span', ArrayHelper::getValue(self::statusList(), $status), [
            'class' => $class,
        ]);
    }

    /**
     * @return array
     */
    public static function inputExperienceList(): array
    {
        $years = range(1980, date('Y'));
        return array_reverse($years, true);
    }

    /**
     * @param int $id
     * @return string
     */
    public static function getExperience(int $id): string
    {
        $inputExperienceList = self::inputExperienceList();
        if (isset($inputExperienceList[$id])) {
            return $inputExperienceList[$id];
        }
        return false;
    }
}
