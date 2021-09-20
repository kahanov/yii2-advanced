<?php

namespace backend\forms\geo\country;

use yii\base\Model;
use common\models\geo\Country;
use yii\data\ActiveDataProvider;

class CountrySearch extends Model
{
    public $id;
    public $title;
    public $phone_code;

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['title'], 'string'],
            [['phone_code'], 'integer'],
        ];
    }

    /**
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search(array $params): ActiveDataProvider
    {
        $query = Country::find();
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
        ]);
        $query->andFilterWhere(['like', 'title', $this->title]);
        $query->andFilterWhere(['like', 'phone_code', $this->phone_code]);
        return $dataProvider;
    }
}
