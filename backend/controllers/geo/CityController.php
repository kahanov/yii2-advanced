<?php

namespace backend\controllers\geo;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\geo\City;
use common\services\geo\CityService;
use backend\forms\geo\city\CitySearch;
use backend\forms\geo\city\CityForm;
use yii\web\Response;
use yii\helpers\ArrayHelper;
use common\services\geo\GeoIndexerService;

class CityController extends Controller
{
    private $service;
    private $indexer;

    /**
     * CityController constructor.
     * @param $id
     * @param $module
     * @param CityService $service
     * @param GeoIndexerService $indexer
     * @param array $config
     */
    public function __construct($id, $module, CityService $service, GeoIndexerService $indexer, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
        $this->indexer = $indexer;
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
     * @param int|NULL $region_id
     * @param int|NULL $district_id
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function actionIndex(int $country_id = NULL, int $region_id = NULL, int $district_id = NULL)
    {
        $searchModel = new CitySearch();
        $formName = $searchModel->formName();
        $queryParams = Yii::$app->request->queryParams;
        if ($country_id) {
            $queryParams[$formName]['country_id'] = $country_id;
        }
        if ($region_id) {
            $queryParams[$formName]['region_id'] = $region_id;
        }
        if ($district_id) {
            $queryParams[$formName]['district_id'] = $district_id;
        }
        $dataProvider = $searchModel->search($queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'country_id' => ($country_id) ? $country_id : NULL,
            'region_id' => ($region_id) ? $region_id : NULL,
            'district_id' => ($district_id) ? $district_id : NULL,
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
     * @param int|NULL $region_id
     * @param int|NULL $district_id
     * @return array|string|Response
     */
    public function actionCreate(int $country_id = NULL, int $region_id = NULL, int $district_id = NULL)
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return $this->choice($region_id, $district_id);
        }
        $form = new CityForm();
        $form->country_id = ($country_id) ? $country_id : NULL;
        $form->region_id = ($region_id) ? $region_id : NULL;
        $form->district_id = ($district_id) ? $district_id : NULL;
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $city = $this->service->save($form);
                $data = [
                    'id' => $city->id,
                    'title' => $city->title,
                    'country_id' => $city->country_id,
                    'region_id' => $city->region_id,
                    'district_id' => $city->district_id,
                    'coordinate_x' => $city->coordinate_x,
                    'coordinate_y' => $city->coordinate_y,
                ];
                $this->indexer->index('city_rt', $data);

                return $this->redirect(['view', 'id' => $city->id]);
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
     * @return array|string|Response
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $city = $this->findModel($id);
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            return $this->choice($city->region_id, $city->district_id);
        }

        $form = new CityForm($city);
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->save($form, $city);
                $data = [
                    'id' => $city->id,
                    'title' => $city->title,
                    'country_id' => $city->country_id,
                    'region_id' => $city->region_id,
                    'district_id' => $city->district_id,
                    'coordinate_x' => $city->coordinate_x,
                    'coordinate_y' => $city->coordinate_y,
                ];
                $this->indexer->index('city_rt', $data);

                return $this->redirect(['view', 'id' => $city->id]);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('update', [
            'model' => $form,
            'city' => $city,
        ]);
    }

    /**
     * @param int|NULL $region_id
     * @param int|NULL $district_id
     * @return array
     */
    private function choice(int $region_id = NULL, int $district_id = NULL)
    {
        $selected = NULL;
        $post = Yii::$app->request->post('depdrop_all_params');
        $result = [];
        if (!empty($post)) {
            $form = new CityForm();
            $formCountryId = (isset($post['geocityform-country_id'])) ? intval($post['geocityform-country_id']) : NULL;
            $formRegionId = (isset($post['geocityform-region_id'])) ? intval($post['geocityform-region_id']) : NULL;
            if (!empty($formCountryId) && empty($formRegionId)) {
                $selected = ($region_id) ? $region_id : NULL;
                $data = $form->getRegionList($formCountryId);
            }
            if (!empty($formRegionId)) {
                $selected = ($district_id) ? $district_id : NULL;
                $data = $form->getDistrictList($formRegionId);
            }
        }
        if (!empty($data)) {
            $result[] = ArrayHelper::getColumn($data, function ($element) {
                return ['id' => $element['id'], 'name' => $element['title']];
            });
        }

        return ['output' => $result, 'selected' => $selected];
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
        GeoIndexerService::deleteSphinxIndex('city_rt', $id);

        return $this->redirect(['index']);
    }

    /**
     * @param $id
     * @return City|null
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = City::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException(Yii::t('common', 'Запрашиваемая страница не существует'));
    }
}
