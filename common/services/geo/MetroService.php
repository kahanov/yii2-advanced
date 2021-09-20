<?php

namespace common\services\geo;

use backend\forms\geo\metro\MetroForm;
use common\models\geo\Metro;
use Yii;

class MetroService
{

    /**
     * @param MetroForm $form
     * @param Metro|NULL $metro
     * @return Metro
     */
    public function save(MetroForm $form, Metro $metro = NULL): Metro
	{
        if (!$metro) {
            $metro = new Metro();
        }
		$metro->title = $form->title;
		$metro->country_id = $form->country_id;
		$metro->region_id = $form->region_id;
		$metro->city_id = $form->city_id;
		$metro->coordinate_x = $form->coordinate_x;
		$metro->coordinate_y = $form->coordinate_y;
		$metro->sort = $form->sort;
        if (!$metro->save()) {
            throw new \RuntimeException(Yii::t('common', 'Не удалось сохранить'));
        }
		return $metro;
	}
	
	/**
	 * @param $id
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
    public function remove($id): void
    {
        if (!$metro = Metro::findOne($id)) {
            throw new \DomainException(Yii::t('common', 'Данные не найдены'));
        }
        if (!$metro->delete()) {
            throw new \RuntimeException(Yii::t('common', 'Не удалось удалить'));
        }
    }
}
