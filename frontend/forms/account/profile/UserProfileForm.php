<?php

namespace frontend\forms\account\profile;

use Yii;
use yii\base\Model;
use common\models\user\User;

class UserProfileForm extends Model
{
    public $profile_type = 1;
    public $first_name;
    public $last_name;
    public $avatar;
    public $date_birth;
    public $facebook;
    public $vk;
    public $ok;
    public $address;
    public $country_id;
    public $region_id;
    public $district_id;
    public $city_id;
    public $street;
    public $street_id;
    public $house_number;
    public $coordinates;

    public $_user;

    /**
     * UserProfileForm constructor.
     * @param User $user
     * @param array $config
     * @throws \yii\base\InvalidConfigException
     */
    public function __construct(User $user, $config = [])
    {
        $this->first_name = $user->first_name;
        $this->last_name = $user->last_name;
        $this->avatar = $user->avatar;
        $this->date_birth = (!empty($user->date_birth)) ? Yii::$app->formatter->asDate($user->date_birth, 'php:Y-m-d') : NULL;
        $this->facebook = $user->facebook;
        $this->vk = $user->vk;
        $this->ok = $user->ok;

        $this->_user = $user;

        parent::__construct($config);
    }

    /**
     * @return string
     */
    public function formName(): string
    {
        return '';
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['first_name', 'last_name'], 'required'],
            [['first_name', 'last_name'], 'string'],
            [['avatar'], 'string'],
            [['profile_type'], 'integer'],
            [['facebook', 'vk', 'ok'], 'url', 'defaultScheme' => 'http'],
            [['date_birth'], 'date', 'format' => 'php:Y-m-d'],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels(): array
    {
        return [
            'first_name' => Yii::t('common', 'Имя'),
            'last_name' => Yii::t('common', 'Фамилия'),
            'avatar' => Yii::t('common', 'Фото'),
            'date_birth' => Yii::t('common', 'Дата рождения'),
            'facebook' => Yii::t('common', 'Facebook'),
            'vk' => Yii::t('common', 'Вконтакте'),
            'ok' => Yii::t('common', 'Одноклассники'),
            'profile_type' => Yii::t('common', 'Я'),
        ];
    }
}
