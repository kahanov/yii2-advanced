<?php

namespace backend\forms\article;

use common\models\article\ArticleCategory;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class CategorySearch extends Model
{
	public $id;
	public $name;
	
	public function rules(): array
	{
		return [
			[['id'], 'integer'],
			[['name'], 'safe'],
		];
	}
	
	/**
	 * @param array $params
	 * @return ActiveDataProvider
	 */
	public function search(array $params): ActiveDataProvider
	{
		$query = ArticleCategory::find();
		
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
			'sort' => [
				'defaultOrder' => ['sort' => SORT_ASC]
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
		
		$query->andFilterWhere(['like', 'name', $this->name]);
		
		return $dataProvider;
	}
}
