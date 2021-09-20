<?php

namespace backend\forms\geo\metro;

use common\models\geo\Metro;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class MetroSearch extends Model
{
    public $id;
    public $title;
    public $region_id;
    public $country_id;
    public $city_id;

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['title'], 'string'],
            [['id', 'region_id', 'country_id', 'city_id'], 'integer'],
        ];
    }

    /**
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search(array $params): ActiveDataProvider
    {
        $query = Metro::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['title' => SORT_ASC]
            ]
        ]);
        $this->load($params);
        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }
        $query->andFilterWhere([
            'id' => $this->id,
            'region_id' => $this->region_id,
            'country_id' => $this->country_id,
            'city_id' => $this->city_id,
        ]);
        $query->andFilterWhere(['like', 'title', $this->title]);
        return $dataProvider;
    }
}
