<?php

namespace frontend\forms;

use yii\base\Model;
use common\models\geo\Region;
use yii\helpers\ArrayHelper;

class ChoiceCityForm extends Model
{
	public $region_id;
	public $url;
	
	/**
	 * @return array
	 */
	public function rules(): array
	{
		return [
			[['url', 'region_id'], 'required'],
			[['region_id'], 'integer'],
			[['url'], 'string'],
		];
	}
	
	/**
	 * @param int $countryId
	 * @return array
	 */
	public function regionList(int $countryId): array
	{
		$regionList = Region::find()->where(['country_id' => $countryId])->orderBy('title')->asArray()->all();

		return ArrayHelper::map($regionList, 'id', 'title');
	}
}
