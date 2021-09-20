<?php

namespace backend\forms\geo\metro;

use common\models\geo\City;
use common\models\geo\Metro;
use Yii;
use yii\base\Model;
use common\models\geo\Region;
use yii\helpers\ArrayHelper;
use common\models\geo\Country;

class MetroForm extends Model
{
    public $title;
    public $country_id;
    public $region_id;
    public $city_id;
    public $coordinate_x;
    public $coordinate_y;
    public $sort = 255;

    private $_metro;

    /**
     * MetroForm constructor.
     * @param Metro|null $metro
     * @param array $config
     */
    public function __construct(Metro $metro = null, $config = [])
    {
        if ($metro) {
            $this->title = $metro->title;
            $this->country_id = $metro->country_id;
            $this->region_id = $metro->region_id;
            $this->city_id = $metro->city_id;
            $this->coordinate_x = $metro->coordinate_x;
            $this->coordinate_y = $metro->coordinate_y;
            $this->sort = $metro->sort;

            $this->_metro = $metro;
        }
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['title', 'city_id', 'region_id', 'country_id'], 'required'],
            [['region_id', 'country_id', 'city_id', 'sort'], 'integer'],
            [['title', 'coordinate_x', 'coordinate_y'], 'string', 'max' => 255],
            [
                ['title'], 'unique',
                'targetAttribute' => ['title', 'region_id', 'country_id', 'city_id'],
                'targetClass' => Metro::class, 'message' => Yii::t('backend/geo', 'Метро уже существует'),
                'filter' => $this->_metro ? ['<>', 'id', $this->_metro->id] : null
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
            'city_id' => Yii::t('backend/geo', 'Город'),
            'coordinate_x' => Yii::t('backend/geo', 'Координаты по осе X'),
            'coordinate_y' => Yii::t('backend/geo', 'Координаты по осе Y'),
            'sort' => Yii::t('backend/geo', 'Порядковый номер'),
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

    public function getCityList(int $region_id = NULL): array
    {
        $geoDistrict = City::find()->select('id, title')->andWhere(['region_id' => $region_id])->orderBy('title')->asArray()->all();
        return $geoDistrict;
    }
}
