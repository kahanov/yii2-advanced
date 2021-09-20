<?php

namespace backend\controllers\geo;

use backend\forms\geo\metro\MetroForm;
use backend\forms\geo\metro\MetroSearch;
use common\models\geo\Metro;
use common\services\geo\MetroService;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\helpers\ArrayHelper;

class MetroController extends Controller
{
    private $service;

    /**
     * MetroController constructor.
     * @param $id
     * @param $module
     * @param MetroService $service
     * @param array $config
     */
    public function __construct($id, $module, MetroService $service, $config = [])
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
     * @param int|NULL $region_id
     * @param int|NULL $city_id
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function actionIndex(int $country_id = NULL, int $region_id = NULL, int $city_id = NULL)
    {
        $searchModel = new MetroSearch();
        $formName = $searchModel->formName();
        $queryParams = Yii::$app->request->queryParams;
        if ($country_id) {
            $queryParams[$formName]['country_id'] = $country_id;
        }
        if ($region_id) {
            $queryParams[$formName]['region_id'] = $region_id;
        }
        if ($city_id) {
            $queryParams[$formName]['city_id'] = $city_id;
        }
        $dataProvider = $searchModel->search($queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'country_id' => ($country_id) ? $country_id : NULL,
            'region_id' => ($region_id) ? $region_id : NULL,
            'city_id' => ($city_id) ? $city_id : NULL,
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
     * @param int|NULL $city_id
     * @return array|string|Response
     */
    public function actionCreate(int $country_id = NULL, int $region_id = NULL, int $city_id = NULL)
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return $this->choice($region_id, $city_id);
        }
        $form = new MetroForm();
        $form->country_id = ($country_id) ? $country_id : NULL;
        $form->region_id = ($region_id) ? $region_id : NULL;
        $form->city_id = ($city_id) ? $city_id : NULL;
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $metro = $this->service->save($form);

                return $this->redirect(['view', 'id' => $metro->id]);
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
        $metro = $this->findModel($id);
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return $this->choice($metro->region_id, $metro->city_id);
        }
        $form = new MetroForm($metro);
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->save($form, $metro);

                return $this->redirect(['view', 'id' => $metro->id]);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('update', [
            'model' => $form,
            'metro' => $metro,
        ]);
    }

    /**
     * @param int|NULL $region_id
     * @param int|NULL $city_id
     * @return array
     */
    private function choice(int $region_id = NULL, int $city_id = NULL)
    {
        $selected = NULL;
        $post = Yii::$app->request->post('depdrop_all_params');
        $result = [];
        if (!empty($post)) {
            $form = new MetroForm();
            $formCountryId = (isset($post['geometroform-country_id'])) ? intval($post['geometroform-country_id']) : NULL;
            $formRegionId = (isset($post['geometroform-region_id'])) ? intval($post['geometroform-region_id']) : NULL;
            $formCityId = (isset($post['geometroform-city_id'])) ? intval($post['geometroform-city_id']) : NULL;
            if (!empty($formCountryId) && empty($formRegionId)) {
                $selected = ($region_id) ? $region_id : NULL;
                $data = $form->getRegionList($formCountryId);
            }
            if (!empty($formRegionId && empty($formCityId))) {
                $selected = ($city_id) ? $city_id : NULL;
                $data = $form->getCityList($formRegionId);
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

        return $this->redirect(['index']);
    }

    /**
     * @param $id
     * @return Metro|null
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = Metro::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException(Yii::t('common', 'Запрашиваемая страница не существует'));
    }
}
