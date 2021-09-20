<?php

namespace common\models\article;

use Yii;
use yii\db\ActiveRecord;

/**
 * The ArticleCategory entity
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property int $sort
 * @property string $title
 * @property string $description
 *
 */
class ArticleCategory extends ActiveRecord
{
	
	/**
	 * @return string
	 */
	public static function tableName(): string
	{
		return 'article_category';
	}
	
	/**
	 * @return array
	 */
	public function attributeLabels(): array
	{
		return [
			'id' => Yii::t('common', 'Идентификатор'),
			'name' => Yii::t('common', 'Название'),
			'slug' => Yii::t('common', 'Название транслитом'),
			'sort' => Yii::t('backend/common', 'Порядковый номер'),
			'title' => Yii::t('backend/common', 'SEO title'),
			'description' => Yii::t('backend/common', 'SEO description'),
		];
	}
}
