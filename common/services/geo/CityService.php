<?php

namespace common\services\geo;

use common\models\geo\City;
use backend\forms\geo\city\CityForm;
use Yii;

class CityService
{

    /**
     * @param CityForm $form
     * @param City|NULL $city
     * @return City
     */
    public function save(CityForm $form, City $city = NULL): City
    {
        $isNew = false;
        if (!$city) {
            $isNew = true;
            $city = new City();
        }
        $city->title = $form->title;
        $city->v_title = $form->v_title;
        $city->country_id = $form->country_id;
        $city->region_id = $form->region_id;
        $city->district_id = $form->district_id;
        $city->coordinate_x = $form->coordinate_x;
        $city->coordinate_y = $form->coordinate_y;
        $city->slug = $form->slug;
        if (!$city->save()) {
            throw new \RuntimeException(Yii::t('common', 'Не удалось сохранить'));
        }

        if ($form->slug_prefix && $isNew) {
            $city->slug = $form->slug . '-' . $city->id;
            if (!$city->save()) {
                throw new \RuntimeException(Yii::t('common', 'Не удалось сохранить'));
            }
        }

        return $city;
    }

    /**
     * @param $id
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function remove($id): void
    {
        if (!$city = City::findOne($id)) {
            throw new \DomainException(Yii::t('common', 'Данные не найдены'));
        }
        if (!$city->delete()) {
            throw new \RuntimeException(Yii::t('common', 'Не удалось удалить'));
        }
    }
}
