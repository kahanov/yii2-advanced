<?php

namespace common\models\geo;

use common\services\geo\GeoService;
use Yii;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property string $title Наименование
 * @property string $v_title Название в дательном падеже
 * @property string $slug Наименование транслитом
 * @property string $phone_code Телефонный код
 * @property string $currency_code Код валюты
 * @property string $currency_name Название валюты
 * @property string $language Язык
 * @property string $coordinates
 *
 */
class Country extends ActiveRecord
{
	/**
	 * @return string
	 */
	public static function tableName(): string
	{
		return 'country';
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

    /**
     * @return array
     */
    public function getCoordinates(): array
    {
        $coordinates = [];
        $data = GeoService::getIsOpenStreetMapCity($this->title);
        if (!empty($data) && !empty($data['coordinates'])) {
            $coordinates = $data['coordinates'];
            $this->coordinates = implode(',', $coordinates);
            $this->save();
        }
        return $coordinates;
    }
}
