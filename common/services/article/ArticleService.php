<?php

namespace common\services\article;

use backend\forms\article\ArticleForm;
use common\models\article\Article;
use frontend\forms\ArticleSearch;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;

class ArticleService
{
    /**
     * @param ArticleForm $formArticle
     * @param Article|NULL $article
     * @return Article
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function save(ArticleForm $formArticle, Article $article = NULL): Article
    {
        if (!$article) {
            $article = new Article();
        }
        $article->name = $formArticle->name;
        $article->category_id = $formArticle->category_id;
        $article->slug = $formArticle->slug;
        $article->status = $formArticle->status;
        $article->title = $formArticle->title;
        $article->description = $formArticle->description;
        $article->content = $formArticle->content;
        $article->anons = $formArticle->anons;
        if ($formArticle->photo) {
            $article->addPhoto($formArticle->photo);
        }
        if (!$article->save()) {
            throw new \RuntimeException(Yii::t('common', 'Не удалось сохранить'));
        }
        return $article;
    }

    /**
     * @param $id
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function remove($id): void
    {
        $article = $this->get($id);
        if (!$article->delete()) {
            throw new \RuntimeException(Yii::t('common', 'Не удалось удалить'));
        }
    }

    /**
     * @param $id
     * @return Article
     * @throws NotFoundHttpException
     */
    public function get($id): Article
    {
        if (!$article = Article::findOne($id)) {
            throw new NotFoundHttpException(Yii::t('common', 'Данные не найдены'));
        }
        return $article;
    }

    /**
     * @return ActiveDataProvider
     */
    public function getNews(): ActiveDataProvider
    {
        $query = Article::find()->orderBy(['id' => SORT_ASC])->with('photo')->cache(10800)->limit(3);
        $query->andWhere(['status' => Article::STATUS_ACTIVE]);
        $query->andWhere(['category_id' => 2]);
        $query->groupBy('id');

        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
            'sort' => false,
        ]);
    }

    /**
     * @param ArticleSearch $form
     * @return ActiveDataProvider
     */
    public function search(ArticleSearch $form): ActiveDataProvider
    {
        $pagination = new Pagination([
            'defaultPageSize' => 10,
            'pageSizeLimit' => [10, 100],
            'validatePage' => false,
            'forcePageParam' => false,
        ]);
        $query = Article::find()->with('category')->orderBy(['id' => SORT_DESC]);
        $query->andWhere(['status' => Article::STATUS_ACTIVE]);

        if (!empty($form->category_id)) {
            $query->andWhere(['category_id' => $form->category_id]);
        }

        if (!empty($form->text)) {
            $query->andWhere(['or', ['like', 'content', $form->text], ['like', 'name', $form->text]]);
        }

        $query->groupBy('id');

        return new ActiveDataProvider([
            'query' => $query,
            'totalCount' => $query->count(),
            'pagination' => $pagination,
            'sort' => false,
        ]);
    }
}
