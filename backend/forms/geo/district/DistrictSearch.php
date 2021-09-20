<?php

namespace backend\forms\geo\district;

use yii\base\Model;
use common\models\geo\District;
use yii\data\ActiveDataProvider;

class DistrictSearch extends Model
{
    public $id;
    public $title;
    public $region_id;

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['title'], 'string'],
            [['id', 'region_id'], 'integer'],
        ];
    }

    /**
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search(array $params): ActiveDataProvider
    {
        $query = District::find();
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
        ]);
        $query->andFilterWhere(['like', 'title', $this->title]);
        return $dataProvider;
    }
}
