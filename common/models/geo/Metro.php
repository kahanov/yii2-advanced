<?php

namespace common\models\geo;

use Yii;
use yii\db\ActiveRecord;
use yii\db\ActiveQuery;

/**
 * @property int $id
 * @property string $title Наименование
 * @property int $country_id Id страны
 * @property int $region_id Id региона
 * @property int $city_id Id города
 * @property int $sort
 * @property string $coordinate_x
 * @property string $coordinate_y
 *
 * @property Country $country
 * @property Region $region
 * @property City $city
 */
class Metro extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return 'metro';
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
            'city_id' => Yii::t('backend/geo', 'Город'),
            'title' => Yii::t('backend/geo', 'Название'),
            'district_id' => Yii::t('backend/geo', 'Район'),
            'coordinate_x' => Yii::t('backend/geo', 'Координаты по осе X'),
            'coordinate_y' => Yii::t('backend/geo', 'Координаты по осе Y'),
        ];
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
    public function getCity(): ActiveQuery
    {
        return $this->hasOne(City::class, ['id' => 'city_id']);
    }
}
