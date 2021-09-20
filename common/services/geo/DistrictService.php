<?php

namespace common\services\geo;

use Yii;
use common\models\geo\District;
use backend\forms\geo\district\DistrictForm;

class DistrictService
{
    /**
     * @param DistrictForm $form
     * @param District|NULL $district
     * @return District
     */
    public function save(DistrictForm $form, District $district = NULL): District
	{
        $isNew = false;
        if (!$district) {
            $isNew = true;
            $district = new District();
        }
		$district->title = $form->title;
		$district->region_id = $form->region_id;
		$district->slug = $form->slug;
        if (!$district->save()) {
            throw new \RuntimeException(Yii::t('common', 'Не удалось сохранить'));
        }
		if ($form->slug_prefix && $isNew) {
			$district->slug = $form->slug . '-' . $district->id;
            if (!$district->save()) {
                throw new \RuntimeException(Yii::t('common', 'Не удалось сохранить'));
            }
		}
		return $district;
	}

    /**
     * @param $id
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function remove($id): void
    {
        if (!$district = District::findOne($id)) {
            throw new \DomainException(Yii::t('common', 'Данные не найдены'));
        }
        if (!$district->delete()) {
            throw new \RuntimeException(Yii::t('common', 'Не удалось удалить'));
        }
    }
}
