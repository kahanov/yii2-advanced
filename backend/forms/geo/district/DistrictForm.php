<?php

namespace backend\forms\geo\district;

use common\models\geo\District;
use Yii;
use yii\base\Model;
use common\models\geo\Region;
use yii\helpers\ArrayHelper;

class DistrictForm extends Model
{
    public $title;
    public $slug;
    public $region_id;
    public $slug_prefix;

    private $_district;

    /**
     * DistrictForm constructor.
     * @param District|null $district
     * @param array $config
     */
    public function __construct(District $district = null, $config = [])
    {
        if ($district) {
            $this->title = $district->title;
            $this->slug = $district->slug;
            $this->region_id = $district->region_id;

            $this->_district = $district;
        }
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['title', 'slug', 'region_id'], 'required'],
            [['region_id'], 'integer'],
            [['title', 'slug'], 'string', 'max' => 255],
            [['slug_prefix'], 'boolean'],
            [
                ['title'], 'unique',
                'targetAttribute' => ['title', 'region_id'],
                'targetClass' => District::class, 'message' => Yii::t('backend/geo', 'Район уже существует'),
                'filter' => $this->_district ? ['<>', 'id', $this->_district->id] : null
            ],
            [
                ['slug'], 'unique',
                'targetClass' => District::class,
                'message' => Yii::t('backend/geo', 'Транслит должен быть уникальным'),
                'filter' => $this->_district ? ['<>', 'id', $this->_district->id] : null
            ],
        ];
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
            'slug_prefix' => Yii::t('backend/geo', 'Добавлять префикс с ID'),
        ];
    }

    /**
     * @return array
     */
    public function getRegionList(): array
    {
        $geoRegion = Region::find()->orderBy('title')->asArray()->all();
        return ArrayHelper::map($geoRegion, 'id', 'title');
    }
}
