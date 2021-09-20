<?php

namespace backend\forms\geo\city;

use common\models\geo\District;
use common\models\geo\City;
use Yii;
use yii\base\Model;
use common\models\geo\Region;
use yii\helpers\ArrayHelper;
use common\models\geo\Country;

class CityForm extends Model
{
	public $title;
	public $slug;
	public $region_id;
	public $country_id;
	public $district_id;
	public $v_title;
	public $coordinate_x;
	public $coordinate_y;
	public $slug_prefix;
	public $main_city_region;
	
	private $_city;
	
	/**
	 * CityForm constructor.
	 * @param City|null $city
	 * @param array $config
	 */
	public function __construct(City $city = null, $config = [])
	{
		if ($city) {
			$this->title = $city->title;
			$this->slug = $city->slug;
			$this->region_id = $city->region_id;
			$this->country_id = $city->country_id;
			$this->district_id = $city->district_id;
			$this->v_title = $city->v_title;
			$this->main_city_region = $city->main_city_region;
			$this->coordinate_x = $city->coordinate_x;
			$this->coordinate_y = $city->coordinate_y;
			
			$this->_city = $city;
		}
		parent::__construct($config);
	}

    /**
     * @return array
     */
    public function rules(): array
	{
		return [
			[['title', 'slug', 'region_id', 'country_id', 'main_city_region'], 'required'],
			[['region_id', 'country_id', 'district_id'], 'integer'],
			[['title', 'slug', 'v_title', 'coordinate_x', 'coordinate_y'], 'string', 'max' => 255],
			[['slug_prefix', 'main_city_region'], 'boolean'],
			[
				['title'], 'unique',
				'targetAttribute' => ['title', 'region_id', 'country_id', 'district_id'],
				'targetClass' => City::class, 'message' => Yii::t('backend/geo', 'Населенный пункт уже существует'),
				'filter' => $this->_city ? ['<>', 'id', $this->_city->id] : null
			],
			[
				['slug', 'coordinate_x', 'coordinate_y'], 'unique',
				'targetClass' => City::class,
				'message' => Yii::t('backend/geo', 'Должно быть уникальным'),
				'filter' => $this->_city ? ['<>', 'id', $this->_city->id] : null
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
			'region_id' => Yii::t('backend/geo', 'Регион'),
			'title' => Yii::t('backend/geo', 'Название'),
			'v_title' => Yii::t('backend/geo', 'Название в дательном падеже'),
			'slug' => Yii::t('backend/geo', 'Название транслитом'),
			'district_id' => Yii::t('backend/geo', 'Район'),
			'coordinate_x' => Yii::t('backend/geo', 'Координаты по осе X'),
			'coordinate_y' => Yii::t('backend/geo', 'Координаты по осе Y'),
			'main_city_region' => Yii::t('backend/geo', 'Главный город региона'),
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
	
	/**
	 * @param int|NULL $country_id
	 * @return array
	 */
	public function getRegionList(int $country_id = NULL): array
	{
		$geoRegion = Region::find()->select('id, title')->andWhere(['country_id' => $country_id])->orderBy('title')->asArray()->all();
		return $geoRegion;
	}
	
	/**
	 * @param int|NULL $region_id
	 * @return array
	 */
	public function getDistrictList(int $region_id = NULL): array
	{
		$geoDistrict = District::find()->select('id, title')->andWhere(['region_id' => $region_id])->orderBy('title')->asArray()->all();
		return $geoDistrict;
	}
}
