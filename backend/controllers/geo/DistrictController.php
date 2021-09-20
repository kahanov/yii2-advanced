<?php

namespace backend\controllers\geo;

use common\models\geo\Region;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\geo\District;
use common\services\geo\DistrictService;
use backend\forms\geo\district\DistrictSearch;
use backend\forms\geo\district\DistrictForm;

class DistrictController extends Controller
{
    private $service;

    /**
     * DistrictController constructor.
     * @param $id
     * @param $module
     * @param DistrictService $service
     * @param array $config
     */
    public function __construct(
        $id,
        $module,
        DistrictService $service,
        $config = []
    )
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
     * @param int|NULL $region_id
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function actionIndex(int $region_id = NULL)
    {
        $searchModel = new DistrictSearch();
        $formName = $searchModel->formName();
        $queryParams = Yii::$app->request->queryParams;
        $region = NULL;
        if ($region_id) {
            if (!$region = Region::findOne($region_id)) {
                throw new \DomainException(Yii::t('common', 'Данные не найдены'));
            }
            $queryParams[$formName]['region_id'] = $region_id;
        }

        $dataProvider = $searchModel->search($queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'region' => $region,
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
     * @param int|NULL $region_id
     * @return string|\yii\web\Response
     */
    public function actionCreate(int $region_id = NULL)
    {
        $form = new DistrictForm();
        if ($region_id) {
            $form->region_id = $region_id;
        }
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $geoDistrict = $this->service->save($form);

                return $this->redirect(['view', 'id' => $geoDistrict->id]);
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
        $district = $this->findModel($id);
        $form = new DistrictForm($district);
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->save($form, $district);

                return $this->redirect(['view', 'id' => $district->id]);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('update', [
            'model' => $form,
            'district' => $district,
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
     * @return District|null
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = District::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException(Yii::t('common', 'Запрашиваемая страница не существует'));
    }
}
