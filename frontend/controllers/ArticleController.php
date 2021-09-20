<?php

namespace frontend\controllers;

use common\services\article\ArticleService;
use common\models\article\Article;
use frontend\forms\ArticleSearch;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class ArticleController extends Controller
{
    private $articleService;

    /**
     * ArticleController constructor.
     * @param $id
     * @param $module
     * @param ArticleService $articleService
     * @param array $config
     */
    public function __construct($id, $module, ArticleService $articleService, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->articleService = $articleService;
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        $form = new ArticleSearch();
        $queryParams = Yii::$app->request->queryParams;
        $form->load($queryParams);
        $form->validate();
        $dataProvider = $this->articleService->search($form);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchForm' => $form,
        ]);
    }

    /**
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $otherArticles = Article::find()->where(['status' => Article::STATUS_ACTIVE])->andWhere(['<', 'id', $model->id])->orderBy(['id' => SORT_DESC])->limit(5)->all();

        return $this->render('view', [
            'model' => $model,
            'otherArticles' => $otherArticles,
        ]);
    }

    /**
     * @param $id
     * @return Article
     * @throws NotFoundHttpException
     */
    protected function findModel($id): Article
    {
        if (($model = Article::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException(Yii::t('common', 'Запрашиваемая страница не существует'));
    }
}
