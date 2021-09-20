<?php

namespace frontend\controllers;

use common\forms\GeoSearchForm;
use common\models\geo\Country;
use common\models\geo\Region;
use common\models\user\Company;
use common\services\geo\GeoService;
use frontend\forms\ChoiceCityForm;
use Yii;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class HelperController extends Controller
{
    private $geoService;

    /**
     * HelperController constructor.
     * @param $id
     * @param $module
     * @param GeoService $geoService
     * @param array $config
     */
    public function __construct($id, $module, GeoService $geoService, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->geoService = $geoService;
    }

    /**
     * Выбор города
     * @param null $countryId
     * @return array|string
     * @throws NotFoundHttpException
     */
    public function actionChoiceCity($countryId = NULL)
    {
        if (Yii::$app->request->isAjax) {
            session_write_close();
            /** @var \frontend\components\DomainUrlManager $urlManager */
            $urlManager = Yii::$app->getUrlManager();
            $form = new ChoiceCityForm();
            if ($form->load(Yii::$app->request->post()) && $form->validate()) {
                try {
                    $region = Region::findOne($form->region_id);
                    $url = $form->url;
                    $url = $urlManager->createAbsoluteUrl([$url, 'country_id' => $region->country_id, 'region_id' => $region->id]);

                    return $this->redirect($url);
                } catch (\DomainException $e) {
                    Yii::$app->errorHandler->logException($e);
                    Yii::$app->session->setFlash('error', $e->getMessage());
                }
            }
            if ($countryId) {
                $regions = $form->regionList($countryId);
                $tabContent = $this->renderPartial('choice-city/city', [
                    'regions' => $regions,
                    'model' => $form,
                    'countryId' => $countryId,
                ]);

                return Json::encode($tabContent);
            }
            /** @var Country $country */
            $country = $urlManager->country;
            $countryId = $country->id;
            $regions = $form->regionList($countryId);
            $tabContent = $this->renderPartial('choice-city/city', [
                'regions' => $regions,
                'model' => $form,
                'countryId' => $countryId,
            ]);
            $countries = Country::find()/*->where(['id' => 1])*/
            ->all();

            return $this->renderAjax('choice-city/index', [
                'countries' => $countries,
                'countryId' => $countryId,
                'tabContent' => $tabContent,
            ]);
        } else {
            throw new NotFoundHttpException(Yii::t('common', 'Запрашиваемая страница не существует'));
        }
    }

    /**
     * @return string
     * @throws NotFoundHttpException
     * @throws \Throwable
     */
    public function actionMobileMenu()
    {
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('mobile-menu', [
                'categoryMenuItems' => [],
            ]);
        } else {
            throw new NotFoundHttpException(Yii::t('common', 'Запрашиваемая страница не существует'));
        }
    }

    /**
     * Поиск адреса
     * @param string $type
     * @return array|bool
     * @throws NotFoundHttpException
     */
    public function actionSearchAddress($type = 'create')
    {
        if (Yii::$app->request->isAjax) {
            session_write_close();
            GeoService::setCacheYandexLimit();

            $form = new GeoSearchForm();
            if ($form->load(Yii::$app->request->post()) && $form->validate()) {
                try {
                    if ($type == 'create') {
                        $result = $this->geoService->searchAddressCreate($form, false, true);
                    } else {
                        $result = $this->geoService->searchAddress($form);
                    }
                    if (empty($result)) {
                        $result = [
                            'status' => 'err',
                            'error' => Yii::t('app', 'Адрес не найден в справочнике')
                        ];
                    }
                    Yii::$app->response->format = Response::FORMAT_JSON;

                    return $result;
                } catch (\DomainException $e) {
                    Yii::$app->errorHandler->logException($e);
                    Yii::$app->session->setFlash('error', $e->getMessage());
                    $result = [
                        'status' => 'err',
                        'error' => $e->getMessage()
                    ];

                    return $result;
                }
            }

            return false;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'Запрашиваемая страница не существует'));
        }
    }

    /**
     * Получение телефона компании или ...
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionGetPhone()
    {
        if (Yii::$app->request->isAjax) {
            session_write_close();
            $type = (string)Yii::$app->request->post('type');
            $id = (int)Yii::$app->request->post('id');
            $phone = NULL;
            if (!empty($type) && $id > 0) {
                if ($type == 'company') {
                    $phone = Company::findOne($id)->getAttribute('phone');
                } else {
                    $phone = NULL;
                }
            }
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['phone' => $phone];
        } else {
            throw new NotFoundHttpException(Yii::t('common', 'Запрашиваемая страница не существует'));
        }
    }
}
