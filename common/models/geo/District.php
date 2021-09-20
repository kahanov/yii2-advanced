<?php

namespace common\models\geo;

use Yii;
use yii\db\ActiveRecord;
use yii\db\ActiveQuery;

/**
 * The GeoDistrict entity
 *
 * @property int $id
 * @property int $region_id Id региона
 * @property string $title Наименование
 * @property string $slug Наименование транслитом
 *
 * @property Region $region
 */
class District extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return 'district';
    }

    /**
     * @return array
     */
    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('backend/geo', 'Идентификатор'),
            'region_id' => Yii::t('backend/geo', 'Регион'),
            'title' => Yii::t('backend/geo', 'Название'),
            'slug' => Yii::t('backend/geo', 'Название транслитом'),
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getRegion(): ActiveQuery
    {
        return $this->hasOne(Region::class, ['id' => 'region_id']);
    }
}
