<?php

namespace frontend\forms\account\profile;

use common\components\phone\PhoneInputValidator;
use common\models\user\Company;
use common\models\user\User;
use Yii;
use yii\base\Model;

class CompanyForm extends Model
{
    public $profile_type = 2;
    public $name;
    public $experience;
    public $contact_email;
    public $description;
    public $phone;
    public $website;
    public $skype;
    public $operating_time = ['calls' => 0];
    public $logotype;
    public $country_id;
    public $region_id;
    public $district_id;
    public $city_id;
    public $street_id;
    public $house_number;
    public $address;
    public $coordinates;

    public $_user;
    public $_company;

    /**
     * CompanyForm constructor.
     * @param User $user
     * @param Company|NULL $company
     * @param array $config
     */
    public function __construct(User $user, Company $company = NULL, $config = [])
    {
        if ($company) {
            $this->name = $company->name;
            $this->experience = $company->experience;
            $this->contact_email = $company->contact_email;
            $this->description = $company->description;
            $this->phone = $company->phone;
            $this->website = $company->website;
            $this->skype = $company->skype;
            $this->operating_time = $company->operating_time;
            $this->logotype = $company->logotype;
            $this->country_id = $company->country_id;
            $this->region_id = $company->region_id;
            $this->district_id = $company->district_id;
            $this->city_id = $company->city_id;
            $this->street_id = $company->street_id;
            $this->house_number = $company->house_number;
            $this->address = $company->address;
            $this->coordinates = $company->coordinates;

            $this->_company = $company;
        }
        $this->_user = $user;
        parent::__construct($config);
    }

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
            [['name', 'experience', 'phone', 'description', 'address'], 'required'],
            ['contact_email', 'trim'],
            ['contact_email', 'email'],
            [['description'], 'string', 'max' => 3000],
            [['contact_email', 'name', 'logotype'], 'string', 'max' => 255],
            [['experience', 'district_id', 'country_id', 'region_id', 'city_id', 'street_id'], 'integer'],
            [['skype', 'website'], 'url', 'defaultScheme' => 'http'],
            ['operating_time', 'default', 'value' => []],
            [['phone'], PhoneInputValidator::class],
            [
                ['name'], 'unique',
                'targetAttribute' => ['name', 'city_id'],
                'targetClass' => Company::class, 'message' => Yii::t('account', 'Компания с таким названием уже существует в данном населенном пункте'),
                'filter' => $this->_company ? ['<>', 'id', $this->_company->id] : null
            ],
            [['address', 'coordinates', 'house_number'], 'string'],
            ['address', function ($attribute, $params) {
                if (empty($this->country_id)) {
                    $this->addError($attribute, Yii::t('common', 'Пожалуйста, введите страну'));
                } else {
                    if (empty($this->city_id)) {
                        $this->addError($attribute, Yii::t('common', 'Пожалуйста, введите населенный пункт'));
                    } else {
                        if (empty($this->street_id)) {
                            $this->addError($attribute, Yii::t('common', 'Пожалуйста, введите улицу'));
                        } else {
                            if (empty($this->address)) {
                                $this->addError($attribute, Yii::t('common', 'Адрес не найден в справочникемммммммммммм'));
                            }
                            if (empty($this->coordinates)) {
                                $this->addError('address', Yii::t('common', 'Неудалось определить координаты'));
                            }
                        }
                    }
                }
            }],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels(): array
    {
        return [
            'name' => Yii::t('common', 'Компания'),
            'experience' => Yii::t('common', 'На рынке, с'),
            'logotype' => Yii::t('common', 'Логотип'),
            'contact_email' => Yii::t('common', 'Контактный E-mail'),
            'description' => Yii::t('common', 'О компании'),
            'phone' => Yii::t('common', 'Телефон'),
            'website' => Yii::t('common', 'Веб-сайт'),
            'skype' => Yii::t('common', 'Скайп'),
            'operating_time' => Yii::t('common', 'Время работы'),
            'profile_type' => Yii::t('common', 'Я'),
            'address' => Yii::t('common', 'Адрес'),
        ];
    }


}
