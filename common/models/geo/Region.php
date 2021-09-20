<?php

namespace common\models\geo;

use common\services\geo\GeoService;
use Yii;
use yii\db\ActiveRecord;
use yii\db\ActiveQuery;

/**
 * @property int $id
 * @property int $country_id Id страны
 * @property string $title Наименование
 * @property string $v_title Название в дательном падеже
 * @property string $subdomain Поддомен
 * @property int $main_city_id
 * @property string $coordinates
 *
 * @property Country $country
 */
class Region extends ActiveRecord
{
	/**
	 * @return string
	 */
	public static function tableName(): string
	{
		return 'region';
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
		];
	}

    /**
     * @param Country $country
     * @return array
     */
    public function getCoordinates(Country $country): array
    {
        $address = $country->title . ', ' . $this->title;
        //$coordinates = GeoService::getCoordinates($address);
        $coordinates = [];
        $data = GeoService::getIsOpenStreetMapCity($address);
        if (!empty($data) && !empty($data['coordinates'])) {
            $coordinates = $data['coordinates'];
            $this->coordinates = implode(',', $coordinates);
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
}
