<?php

namespace common\services\article;

use backend\forms\article\CategoryForm;
use common\models\article\ArticleCategory;
use Yii;
use yii\web\NotFoundHttpException;

class CategoryService
{
	
	/**
	 * @param CategoryForm $formCategory
	 * @param ArticleCategory|NULL $category
	 * @return ArticleCategory
	 */
	public function save(CategoryForm $formCategory, ArticleCategory $category = NULL): ArticleCategory
	{
		if (!$category) {
			$category = new ArticleCategory();
		}
		$category->name = $formCategory->name;
		$category->slug = $formCategory->slug;
		$category->sort = $formCategory->sort;
		$category->title = $formCategory->title;
		$category->description = $formCategory->description;
        if (!$category->save()) {
            throw new \RuntimeException(Yii::t('common', 'Не удалось сохранить'));
        }
		return $category;
	}

    /**
     * @param $id
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function remove($id): void
	{
        $category = $this->get($id);
        if (!$category->delete()) {
            throw new \RuntimeException(Yii::t('common', 'Не удалось удалить'));
        }
	}

    /**
     * @param $id
     * @return ArticleCategory
     * @throws NotFoundHttpException
     */
    public function get($id): ArticleCategory
    {
        if (!$category = ArticleCategory::findOne($id)) {
            throw new NotFoundHttpException(Yii::t('common', 'Данные не найдены'));
        }
        return $category;
    }
}
