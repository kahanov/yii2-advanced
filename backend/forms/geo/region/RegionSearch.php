<?php

namespace backend\forms\geo\region;

use yii\base\Model;
use common\models\geo\Region;
use yii\data\ActiveDataProvider;

class RegionSearch extends Model
{
    public $id;
    public $title;
    public $country_id;

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['title'], 'string'],
            [['id', 'country_id'], 'integer'],
        ];
    }

    /**
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search(array $params): ActiveDataProvider
    {
        $query = Region::find();
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
            'country_id' => $this->country_id,
        ]);
        $query->andFilterWhere(['like', 'title', $this->title]);
        return $dataProvider;
    }
}
