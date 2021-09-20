<?php

namespace backend\forms\geo\country;

use Yii;
use yii\base\Model;
use common\models\geo\Country;

class CountryForm extends Model
{
	public $title;
	public $v_title;
	public $slug;
	public $phone_code;
	public $currency_code;
	public $currency_name;
	public $language;
	
	private $_country;
	
	/**
	 * CountryForm constructor.
	 * @param Country|null $country
	 * @param array $config
	 */
	public function __construct(Country $country = null, $config = [])
	{
		if ($country) {
			$this->title = $country->title;
			$this->v_title = $country->v_title;
			$this->slug = $country->slug;
			$this->phone_code = $country->phone_code;
			$this->currency_code = $country->currency_code;
			$this->currency_name = $country->currency_name;
			$this->language = $country->language;
			
			$this->_country = $country;
		}
		parent::__construct($config);
	}
	
	public function rules(): array
	{
		return [
			[['title', 'phone_code', 'slug', 'phone_code', 'currency_code', 'currency_name', 'language'], 'required'],
			[['title', 'v_title', 'slug', 'phone_code', 'currency_code', 'currency_name', 'language'], 'string', 'max' => 255],
			[['title'], 'unique', 'targetClass' => Country::class, 'message' => Yii::t('backend/geo', 'Страна уже существует'), 'filter' => $this->_country ? ['<>', 'id', $this->_country->id] : null],
			[['phone_code'], 'unique', 'targetClass' => Country::class, 'message' => Yii::t('backend/geo', 'Телефонный код уже используется'), 'filter' => $this->_country ? ['<>', 'id', $this->_country->id] : null],
			[['slug'], 'unique', 'targetClass' => Country::class, 'message' => Yii::t('backend/geo', 'Транслит должен быть уникальным'), 'filter' => $this->_country ? ['<>', 'id', $this->_country->id] : null],
		];
	}
	
	/**
	 * @return array
	 */
	public function attributeLabels(): array
	{
		return [
			'id' => Yii::t('backend/geo', 'Идентификатор'),
			'title' => Yii::t('backend/geo', 'Название'),
			'v_title' => Yii::t('backend/geo', 'Название в дательном падеже'),
			'slug' => Yii::t('backend/geo', 'Название транслитом'),
			'phone_code' => Yii::t('backend/geo', 'Телефонный код'),
			'currency_code' => Yii::t('backend/geo', 'Код валюты'),
			'currency_name' => Yii::t('backend/geo', 'Название валюты'),
			'language' => Yii::t('backend/geo', 'Язык'),
		];
	}
}
