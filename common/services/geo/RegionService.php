<?php

namespace common\services\geo;

use common\models\geo\Region;
use backend\forms\geo\region\RegionForm;
use Yii;

class RegionService
{

    /**
     * @param RegionForm $form
     * @param Region|NULL $region
     * @return Region
     */
    public function save(RegionForm $form, Region $region = NULL): Region
	{
        if (!$region) {
            $region = new Region();
        }
		$region->title = $form->title;
		$region->v_title = $form->v_title;
		$region->country_id = $form->country_id;
		$region->subdomain = $form->subdomain;
        if (!$region->save()) {
            throw new \RuntimeException(Yii::t('common', 'Не удалось сохранить'));
        }
		return $region;
	}

	/**
	 * @param $id
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
    public function remove($id): void
    {
        if (!$region = Region::findOne($id)) {
            throw new \DomainException(Yii::t('common', 'Данные не найдены'));
        }
        if (!$region->delete()) {
            throw new \RuntimeException(Yii::t('common', 'Не удалось удалить'));
        }
    }
}
