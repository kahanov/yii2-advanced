<?php

namespace backend\controllers\article;

use backend\forms\article\ArticleForm;
use backend\forms\article\ArticleSearch;
use common\models\article\Article;
use common\services\article\ArticleService;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class ArticleController extends Controller
{
	private $service;
	
	/**
	 * ArticleController constructor.
	 * @param $id
	 * @param $module
	 * @param ArticleService $service
	 * @param array $config
	 */
	public function __construct($id, $module, ArticleService $service, $config = [])
	{
		parent::__construct($id, $module, $config);
		$this->service = $service;
	}
	
	/**
	 * @return array
	 */
	public function behaviors(): array
	{
		return [
			'verbs' => [
				'class' => VerbFilter::class,
				'actions' => [
					'delete' => ['POST'],
					'delete-photo' => ['POST'],
				],
			],
		];
	}
	
	/**
	 * @return string
	 */
	public function actionIndex()
	{
		$searchModel = new ArticleSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		return $this->render('index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}
	
	/**
	 * @param $id
	 * @return string
	 * @throws NotFoundHttpException
	 */
	public function actionView($id)
	{
		return $this->render('view', [
			'model' => $this->findModel($id),
		]);
	}
	
	/**
	 * @return string|\yii\web\Response
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function actionCreate()
	{
		$form = new ArticleForm();
		if ($form->load(Yii::$app->request->post()) && $form->validate()) {
			try {
				$post = $this->service->save($form);

				return $this->redirect(['view', 'id' => $post->id]);
			} catch (\DomainException $e) {
				Yii::$app->errorHandler->logException($e);
				Yii::$app->session->setFlash('error', $e->getMessage());
			}
		}

		return $this->render('create', [
			'model' => $form,
		]);
	}
	
	/**
	 * @param $id
	 * @return string|\yii\web\Response
	 * @throws NotFoundHttpException
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function actionUpdate($id)
	{
		$article = $this->findModel($id);
		$form = new ArticleForm($article);
		if ($form->load(Yii::$app->request->post()) && $form->validate()) {
			try {
				$this->service->save($form, $article);

				return $this->refresh();
			} catch (\DomainException $e) {
				Yii::$app->errorHandler->logException($e);
				Yii::$app->session->setFlash('error', $e->getMessage());
			}
		}

		return $this->render('update', [
			'model' => $form,
			'article' => $article,
		]);
	}
	
	/**
	 * @param $id
	 * @return \yii\web\Response
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function actionDelete($id)
	{
		try {
			$this->service->remove($id);
		} catch (\DomainException $e) {
			Yii::$app->session->setFlash('error', $e->getMessage());
		}

		return $this->redirect(['index']);
	}
	
	/**
	 * @param $articleId
	 * @return string
	 * @throws NotFoundHttpException
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function actionDeletePhoto($articleId)
	{
		$article = $this->findModel($articleId);
		if ($photo = $article->photo) {
			$photo->delete();
		}
		$success = true;

		return json_encode($success);
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
