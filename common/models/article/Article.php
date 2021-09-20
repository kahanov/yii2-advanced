<?php

namespace common\models\article;

use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\web\UploadedFile;
use yii\helpers\ArrayHelper;

/**
 * The Article entity
 *
 * @property int $id
 * @property int $category_id
 * @property string $name
 * @property string $slug
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property string $title
 * @property string $description
 * @property string $content
 * @property string $anons
 *
 * @property Photo $photo
 * @property Photo $photos
 * @property ArticleCategory $category
 */
class Article extends ActiveRecord
{
	const STATUS_DRAFT = 0;
	const STATUS_ACTIVE = 1;
	
	/**
	 * @return string
	 */
	public static function tableName(): string
	{
		return 'article';
	}
	
	/**
	 * @return array
	 */
	public function behaviors(): array
	{
		return [
			TimestampBehavior::class,
			[
				'class' => SaveRelationsBehavior::class,
				'relations' => ['photos'],
			],
		];
	}
	
	/**
	 * @return bool
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function beforeDelete(): bool
	{
		if (parent::beforeDelete()) {
			if ($this->photo) {
				$this->photo->delete();
			}
			return true;
		}
		return false;
	}
	
	/**
	 * @return array
	 */
	public function attributeLabels(): array
	{
		return [
			'id' => Yii::t('common', 'Идентификатор'),
			'category_id' => Yii::t('common', 'Категория'),
			'name' => Yii::t('common', 'Название'),
			'slug' => Yii::t('common', 'Название транслитом'),
			'status' => Yii::t('common', 'Статус'),
			'created_at' => Yii::t('common', 'Дата создания'),
			'updated_at' => Yii::t('common', 'Дата обновления'),
			'title' => Yii::t('backend/common', 'SEO title'),
			'description' => Yii::t('backend/common', 'SEO description'),
			'content' => Yii::t('backend/common', 'Содержание'),
			'photo' => Yii::t('backend/common', 'Фото'),
		];
	}
	
	/**
	 * @param UploadedFile $file
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function addPhoto(UploadedFile $file): void
	{
		$photos = $this->photos;
		foreach ($photos as $i => $photo) {
			unset($photos[$i]);
		}
		$photos[] = Photo::create($file);
		$this->photos = $photos;
	}
	
	/**
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function removePhoto(): void
	{
		$this->photo->delete();
	}
	
	/**
	 * @return array
	 */
	public function categoriesList(): array
	{
		return ArrayHelper::map(ArticleCategory::find()->orderBy('sort')->asArray()->all(), 'id', 'name');
	}
	
	/**
	 * @return ActiveQuery
	 */
	public function getCategory(): ActiveQuery
	{
		return $this->hasOne(ArticleCategory::class, ['id' => 'category_id']);
	}
	
	/**
	 * @return ActiveQuery
	 */
	public function getPhoto(): ActiveQuery
	{
		return $this->hasOne(Photo::class, ['article_id' => 'id']);
	}
	
	/**
	 * @return ActiveQuery
	 */
	public function getPhotos(): ActiveQuery
	{
		return $this->hasMany(Photo::class, ['article_id' => 'id']);
	}
}
