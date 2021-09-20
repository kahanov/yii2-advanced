<?php

namespace backend\controllers\geo;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\geo\Country;
use common\services\geo\CountryService;
use backend\forms\geo\country\CountrySearch;
use backend\forms\geo\country\CountryForm;

class DefaultController extends Controller
{
    private $service;

    /**
     * DefaultController constructor.
     * @param $id
     * @param $module
     * @param CountryService $service
     * @param array $config
     */
    public function __construct($id, $module, CountryService $service, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new CountrySearch();
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
     */
    public function actionCreate()
    {
        $form = new CountryForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $geoCountry = $this->service->save($form);

                return $this->redirect(['view', 'id' => $geoCountry->id]);
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
     */
    public function actionUpdate($id)
    {
        $country = $this->findModel($id);
        $form = new CountryForm($country);
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->save($form, $country);

                return $this->redirect(['view', 'id' => $country->id]);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('update', [
            'model' => $form,
            'country' => $country,
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
        $this->service->remove($id);

        return $this->redirect(['index']);
    }

    /**
     * @param $id
     * @return Country|null
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = Country::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException(Yii::t('common', 'Запрашиваемая страница не существует'));
    }
}
