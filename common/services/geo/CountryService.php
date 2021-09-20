<?php

namespace common\services\geo;

use Yii;
use common\models\geo\Country;
use backend\forms\geo\country\CountryForm;

class CountryService
{
    /**
     * @param CountryForm $form
     * @param Country|NULL $country
     * @return Country
     */
    public function save(CountryForm $form, Country $country = NULL): Country
	{
        if (!$country) {
            $country = new Country();
        }
		$country->title = $form->title;
		$country->v_title = $form->v_title;
		$country->slug = $form->slug;
		$country->phone_code = $form->phone_code;
		$country->currency_code = $form->currency_code;
		$country->currency_name = $form->currency_name;
		$country->language = $form->language;
        if (!$country->save()) {
            throw new \RuntimeException(Yii::t('common', 'Не удалось сохранить'));
        }
		return $country;
	}

	/**
	 * @param $id
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function remove($id): void
	{
        if (!$country = Country::findOne($id)) {
            throw new \DomainException(Yii::t('common', 'Данные не найдены'));
        }
        if (!$country->delete()) {
            throw new \RuntimeException(Yii::t('common', 'Не удалось удалить'));
        }
	}
}
