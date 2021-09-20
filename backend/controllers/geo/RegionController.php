<?php

namespace backend\controllers\geo;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\geo\Region;
use common\services\geo\RegionService;
use backend\forms\geo\region\RegionSearch;
use backend\forms\geo\region\RegionForm;

class RegionController extends Controller
{
    private $service;

    /**
     * RegionController constructor.
     * @param $id
     * @param $module
     * @param RegionService $service
     * @param array $config
     */
    public function __construct($id, $module, RegionService $service, $config = [])
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
     * @param int|NULL $country_id
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function actionIndex(int $country_id = NULL)
    {
        $searchModel = new RegionSearch();
        $formName = $searchModel->formName();
        $queryParams = Yii::$app->request->queryParams;
        if ($country_id) {
            $queryParams[$formName]['country_id'] = $country_id;
        }
        $dataProvider = $searchModel->search($queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'country_id' => ($country_id) ? $country_id : NULL,
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
     * @param int|NULL $country_id
     * @return string|\yii\web\Response
     */
    public function actionCreate(int $country_id = NULL)
    {
        $form = new RegionForm();
        if ($country_id) {
            $form->country_id = $country_id;
        }
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $geoRegion = $this->service->save($form);

                return $this->redirect(['view', 'id' => $geoRegion->id]);
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
        $region = $this->findModel($id);
        $form = new RegionForm($region);
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->save($form, $region);

                return $this->redirect(['view', 'id' => $region->id]);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('update', [
            'model' => $form,
            'region' => $region,
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
     * @return Region|null
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = Region::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException(Yii::t('common', 'Запрашиваемая страница не существует'));
    }
}
