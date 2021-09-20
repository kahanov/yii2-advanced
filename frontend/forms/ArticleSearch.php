<?php

namespace frontend\forms;

use common\models\article\ArticleCategory;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class ArticleSearch extends Model
{
    public $text;
    public $category_id;
	
	/**
	 * @return array
	 */
	public function rules(): array
    {
        return [
            [['category_id',], 'integer'],
            [['text'], 'safe'],
        ];
    }
    
	/**
	 * @return string
	 */
	public function formName(): string
	{
		return '';
	}
	
	/**
	 * @return array
	 */
	public function categoriesList(): array
    {
        return ArrayHelper::map(ArticleCategory::find()->orderBy('sort')->asArray()->all(), 'id', 'name');
    }
	
	/**
	 * @return ArticleCategory|null
	 */
	public function getCategory(): ?ArticleCategory
	{
		if (!empty($this->category_id)) {
			return ArticleCategory::findOne($this->category_id);
		}

		return NULL;
	}
}
