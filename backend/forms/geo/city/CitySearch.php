<?php

namespace backend\forms\geo\city;

use yii\base\Model;
use common\models\geo\City;
use yii\data\ActiveDataProvider;

class CitySearch extends Model
{
	public $id;
	public $title;
	public $region_id;
	public $country_id;
	public $district_id;
	
	/**
	 * @inheritdoc
	 */
	public function rules(): array
	{
		return [
			[['title'], 'string'],
			[['id', 'region_id', 'country_id', 'district_id'], 'integer'],
		];
	}
	
	/**
	 * @param array $params
	 * @return ActiveDataProvider
	 */
	public function search(array $params): ActiveDataProvider
	{
		$query = City::find();
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
			'district_id' => $this->district_id,
		]);
		$query->andFilterWhere(['like', 'title', $this->title]);
		return $dataProvider;
	}
}
