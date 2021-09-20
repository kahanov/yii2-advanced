<?php

namespace common\services\user;

use common\models\user\Company;
use frontend\forms\account\profile\CompanyForm;
use frontend\forms\account\profile\LogotypeForm;
use frontend\forms\account\profile\UserProfileForm;
use Yii;
use common\models\user\User;
use backend\forms\user\UserCreateForm;
use frontend\forms\account\profile\AvatarForm;
use common\services\TransactionManager;

/**
 * User profile service
 */
class ProfileService
{
    private $userService;
    private $transaction;

    /**
     * ProfileService constructor.
     * @param UserService $userService
     * @param TransactionManager $transaction
     */
    public function __construct(UserService $userService, TransactionManager $transaction)
    {
        $this->userService = $userService;
        $this->transaction = $transaction;
    }

    /**
     * @param UserCreateForm $form
     * @return User
     * @throws \yii\base\Exception
     */
    public function create(UserCreateForm $form): User
    {
        $user = User::create(
            $form->username,
            $form->email,
            $form->password
        );

        if (!$user->save()) {
            throw new \RuntimeException(Yii::t('common', 'Не удалось сохранить'));
        }

        return $user;
    }

    /**
     * @param User $user
     * @param $form
     * @throws \Throwable
     */
    public function edit(User $user, $form): void
    {
        switch ($form->profile_type) {
            case 2:
                $this->saveCompany($user, $form);
                break;
            default:
                $this->saveProfile($user, $form);
        }
    }

    /**
     * @param User $user
     * @param UserProfileForm $form
     */
    private function saveProfile(User $user, UserProfileForm $form): void
    {
        $user->first_name = $form->first_name;
        $user->last_name = $form->last_name;
        $user->date_birth = (!empty($form->date_birth)) ? strtotime($form->date_birth . ' 00:00:00') : NULL;
        $user->facebook = $form->facebook;
        $user->vk = $form->vk;
        $user->ok = $form->ok;
        if (!$user->save()) {
            throw new \RuntimeException(Yii::t('common', 'Не удалось сохранить'));
        }
    }

    /**
     * @param User $user
     * @param CompanyForm $form
     */
    private function saveCompany(User $user, CompanyForm $form): void
    {
        if (!$company = $user->company) {
            $company = new Company();
            $company->user_id = $user->id;
        }
        $company->name = $form->name;
        $company->experience = $form->experience;
        $company->contact_email = $form->contact_email;
        $company->description = $form->description;
        $company->phone = $form->phone;
        $company->website = $form->website;
        $company->skype = $form->skype;
        $company->operating_time = $form->operating_time;
        $company->logotype = $form->logotype;
        $company->country_id = $form->country_id;
        $company->region_id = $form->region_id;
        $company->district_id = $form->district_id;
        $company->city_id = $form->city_id;
        $company->street_id = $form->street_id;
        $company->house_number = $form->house_number;
        $company->address = $form->address;
        $company->coordinates = $form->coordinates;

        if (!$company->save()) {
            throw new \RuntimeException(Yii::t('common', 'Не удалось сохранить'));
        }
    }

    /**
     * @param AvatarForm $form
     * @return string
     */
    public function uploadAvatar(AvatarForm $form): string
    {
        $avatar = '';
        if (!empty($form->avatar)) {
            $id = Yii::$app->user->id;
            $user = $this->userService->get($id);
            $user->x = $form->x;
            $user->y = $form->y;
            $user->x2 = $form->x2;
            $user->y2 = $form->y2;
            $user->w = $form->w;
            $user->h = $form->h;
            $user->setAvatar($form->avatar);
            if (!$user->save()) {
                throw new \RuntimeException(Yii::t('common', 'Не удалось сохранить'));
            }
            $avatar = $user->getThumbFileUrl('avatar', 'avatar');
        }
        return $avatar;
    }

    /**
     * @param LogotypeForm $form
     * @return string
     */
    public function uploadLogotype(LogotypeForm $form): string
    {
        $logotype = '';
        if (!empty($form->logotype)) {
            $id = Yii::$app->user->id;
            $user = $this->userService->get($id);
            $user->x = $form->x;
            $user->y = $form->y;
            $user->x2 = $form->x2;
            $user->y2 = $form->y2;
            $user->w = $form->w;
            $user->h = $form->h;
            $user->setLogotype($form->logotype);
            if (!$user->save()) {
                throw new \RuntimeException(Yii::t('common', 'Не удалось сохранить'));
            }
            $logotype = $user->getThumbFileUrl('logotype', 'logotype');
        }
        return $logotype;
    }
}
