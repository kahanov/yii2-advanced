<?php

namespace common\forms;

use yii\base\Model;

class GeoSearchForm extends Model
{
    public $country;
    public $region;
    public $district;
    public $city;
    public $street;
    public $house_number;
    public $coordinates;
    public $is_yandex = 0;

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['country', 'region', 'city'], 'required', 'on' => 'import'],
            [['country', 'city'], 'required', 'on' => 'no_region'],
            [['country', 'region', 'district', 'city', 'street', 'coordinates', 'house_number'], 'string'],
            [['is_yandex'], 'integer'],
        ];
    }
}
