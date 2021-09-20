<?php

namespace common\models\geo;

use common\helpers\BaseCommonHelper;
use Yii;
use yii\db\ActiveRecord;
use yii\db\ActiveQuery;

/**
 * @property int $id
 * @property string $name Наименование
 * @property int $city_id Id города
 * @property string $slug
 *
 * @property City $city
 */
class Street extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return 'street';
    }

    /**
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes): void
    {
        if (empty($this->slug)) {
            $this->slugify();
        }
    }

    /**
     * Транслитерация
     */
    private function slugify(): void
    {
        $slug = BaseCommonHelper::slugify($this->name);
        $this->updateAttributes(['slug' => $slug]);
    }

    /**
     * @return array
     */
    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('backend/geo', 'Идентификатор'),
            'city_id' => Yii::t('backend/geo', 'Город'),
            'name' => Yii::t('backend/geo', 'Название'),
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getCity(): ActiveQuery
    {
        return $this->hasOne(City::class, ['id' => 'city_id']);
    }
}
