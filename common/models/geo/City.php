<?php

namespace common\models\geo;

use common\services\geo\GeoService;
use Yii;
use yii\db\ActiveRecord;
use yii\db\ActiveQuery;

/**
 * @property int $id
 * @property int $country_id Id страны
 * @property int $region_id Id региона
 * @property string $title Наименование
 * @property string $v_title Название в дательном падеже
 * @property string $slug Наименование транслитом
 * @property int $district_id Id района
 * @property string $coordinate_x
 * @property string $coordinate_y
 * @property boolean $main_city_region / 1 - является главным городом, 0 - нет
 *
 * @property Country $country
 * @property Region $region
 * @property District $district
 */
class City extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return 'city';
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
     * @param Country $country
     * @param Region $region
     * @return array
     */
    public function getCoordinates(Country $country, Region $region): array
    {
        $address = $country->title . ', ' . $region->title . ', ' . $this->title;
        $coordinates = [];
        //$coordinates = GeoService::getCoordinates($address);
        $data = GeoService::getIsOpenStreetMapCity($address);
        if (!empty($data) && !empty($data['coordinates'])) {
            $coordinates = $data['coordinates'];
            $this->coordinate_x = $coordinates[0];
            $this->coordinate_y = $coordinates[1];
            $this->save();
        }
        return $coordinates;
    }

    /**
     * @return ActiveQuery
     */
    public function getCountry(): ActiveQuery
    {
        return $this->hasOne(Country::class, ['id' => 'country_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getRegion(): ActiveQuery
    {
        return $this->hasOne(Region::class, ['id' => 'region_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getDistrict(): ActiveQuery
    {
        return $this->hasOne(District::class, ['id' => 'district_id']);
    }
}
