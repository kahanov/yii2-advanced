<?php

namespace backend\forms\geo\region;

use common\models\geo\Country;
use Yii;
use yii\base\Model;
use common\models\geo\Region;
use yii\helpers\ArrayHelper;

class RegionForm extends Model
{
	public $title;
	public $v_title;
	public $subdomain;
	public $country_id;
	
	private $_region;
	
	/**
	 * RegionForm constructor.
	 * @param Region|null $region
	 * @param array $config
	 */
	public function __construct(Region $region = null, $config = [])
	{
		if ($region) {
			$this->title = $region->title;
			$this->v_title = $region->v_title;
			$this->subdomain = $region->subdomain;
			$this->country_id = $region->country_id;
			
			$this->_region = $region;
		}
		parent::__construct($config);
	}
	
	public function rules(): array
	{
		return [
			[['title', 'subdomain', 'country_id'], 'required'],
			[['country_id'], 'integer'],
			[['title', 'v_title', 'subdomain'], 'string', 'max' => 255],
			[
				['title'], 'unique',
				'targetAttribute' => ['title', 'country_id'],
				'targetClass' => Region::class, 'message' => Yii::t('backend/geo', 'Регион уже существует'),
				'filter' => $this->_region ? ['<>', 'id', $this->_region->id] : null
			],
			[
				['subdomain'], 'unique',
				'targetClass' => Region::class,
				'message' => Yii::t('backend/geo', 'Транслит должен быть уникальным'),
				'filter' => $this->_region ? ['<>', 'id', $this->_region->id] : null
			],
		];
	}
	
	/**
	 * @return array
	 */
	public function attributeLabels(): array
	{
		return [
			'id' => Yii::t('backend/geo', 'Идентификатор'),
			'country_id' => Yii::t('backend/geo', 'Страна'),
			'title' => Yii::t('backend/geo', 'Название'),
			'v_title' => Yii::t('backend/geo', 'Название в дательном падеже'),
			'subdomain' => Yii::t('backend/geo', 'Поддомен'),
			'slug_prefix' => Yii::t('backend/geo', 'Добавлять префикс с ID'),
		];
	}
	
	/**
	 * @return array
	 */
	public function getCountryList(): array
	{
		$geoCountry = Country::find()->orderBy('title')->asArray()->all();
		return ArrayHelper::map($geoCountry, 'id', 'title');
	}
}
