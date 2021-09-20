<?php

namespace frontend\controllers;

use common\models\user\Company;
use common\services\user\CompanyService;
use frontend\forms\CompanySearchForm;
use frontend\forms\MessageForm;
use Jaybizzle\CrawlerDetect\CrawlerDetect;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use kartik\form\ActiveForm;
use yii\web\Response;

class CompanyController extends Controller
{
    private $companyService;

    /**
     * CompanyController constructor.
     * @param $id
     * @param $module
     * @param CompanyService $companyService
     * @param array $config
     */
    public function __construct(
        $id,
        $module,
        CompanyService $companyService,
        $config = []
    )
    {
        parent::__construct($id, $module, $config);
        $this->companyService = $companyService;
    }

    /**
     * @return array|string|Response
     * @throws \yii\web\ForbiddenHttpException
     */
    public function actionIndex()
    {
        $form = new CompanySearchForm();
        $queryParams = Yii::$app->request->queryParams;
        $form->load($queryParams);
        if (Yii::$app->request->isAjax) {
            session_write_close();
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($form);
        }
        /** @var \frontend\components\DomainUrlManager $urlManager */
        $urlManager = Yii::$app->getUrlManager();
        if (isset($queryParams['region_id'])) {
            if (!empty($queryParams['region_id'])) {
                $url = $urlManager->createAbsoluteUrl(['/agency', 'region_id' => $queryParams['region_id']]);
                return $this->redirect($url);
            } else {
                unset($queryParams['region_id']);
                $url = $urlManager->createAbsoluteUrl(['/agency', 'region_id' => 0]);
                return $this->redirect($url);
            }
        }
        $form->validate();
        $dataProvider = $this->companyService->search($form);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchForm' => $form,
        ]);
    }

    /**
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     * @throws \yii\web\ForbiddenHttpException
     */
    public function actionView($id)
    {
        $company = $this->findModel($id);
        $this->checkUrl($company);
        $messageForm = new MessageForm();

        return $this->render('view', [
            'company' => $company,
            'messageForm' => $messageForm,
        ]);
    }

    /**
     * Редирект на сайт компании
     * @param $id
     * @return Response
     * @throws NotFoundHttpException
     */
    public function actionRedirect($id)
    {
        $company = $this->findModel($id);
        if (!empty($company->website)) {
            return $this->redirect($company->website);
        } else {
            throw new NotFoundHttpException(Yii::t('common', 'Запрашиваемая страница не существует'));
        }
    }

    /**
     * Отправить сообщение на email
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionMessage()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $form = new MessageForm();
            $form->scenario = 'company';
            if ($form->load(Yii::$app->request->post()) && $form->validate()) {
                try {
                    $crawlerDetect = new CrawlerDetect();
                    // Если бот
                    if ($crawlerDetect->isCrawler()) {
                        throw new NotFoundHttpException(Yii::t('common', 'Доступ заблокирован'));
                    } else {
                        if ($form->check === 'nospam') {
                            $result = $this->companyService->sendMessage($form);
                            if ($result) {
                                return ['success' => true];
                            }
                        } else {
                            throw new NotFoundHttpException(Yii::t('common', 'Доступ заблокирован'));
                        }
                    }
                } catch (\DomainException $e) {
                    Yii::$app->errorHandler->logException($e);

                    return ['error' => true];
                }
            }

            return ['error' => true];
        } else {
            throw new NotFoundHttpException(Yii::t('common', 'Запрашиваемая страница не существует'));
        }
    }

    /**
     * Редирект если открывается не на своем регионе
     * @param Company $company
     * @return bool|Response
     */
    private function checkUrl(Company $company)
    {
        /** @var \frontend\components\DomainUrlManager $urlManager */
        $urlManager = Yii::$app->getUrlManager();
        $region = $urlManager->region;
        $url = NULL;
        if (!empty($company->regions)) {
            if (!empty($region)) {
                if ($region->id != $company->region_id) {
                    $url = $urlManager->createAbsoluteUrl(['/company/view', 'id' => $company->id, 'region_id' => $company->region_id]);
                }
            } else {
                $url = $urlManager->createAbsoluteUrl(['/company/view', 'id' => $company->id, 'region_id' => $company->region_id]);
            }
        } else {
            if (!empty($region)) {
                $url = $urlManager->createAbsoluteUrl(['/company/view', 'id' => $company->id, 'region_id' => 0]);
            }
        }
        if ($url) {
            return $this->redirect($url, 301);
        }

        return false;
    }

    /**
     * @param $id
     * @return Company|null
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = Company::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException(Yii::t('common', 'Запрашиваемая страница не существует'));
    }
}
