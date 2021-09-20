<?php

namespace backend\controllers\settings;

use backend\forms\settings\params\ParamsSearch;
use backend\forms\settings\params\ParamsForm;
use common\services\settings\ParamsService;
use Yii;
use yii\web\Controller;
use common\models\Config;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class ParamsController extends Controller
{
    private $service;

    /**
     * ParamsController constructor.
     * @param $id
     * @param $module
     * @param ParamsService $service
     * @param array $config
     */
    public function __construct($id, $module, ParamsService $service, $config = [])
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
                ],
            ],
        ];
    }

    /**
     * Lists all Config models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ParamsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $form = new ParamsForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $param = $this->service->save($form);

                return $this->redirect(['update', 'id' => $param->id]);
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
        $param = $this->findModel($id);
        $form = new ParamsForm($param);
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->save($form, $param);

                return $this->refresh();
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('update', [
            'model' => $form,
            'param' => $param,
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
     * @return bool
     * @throws NotFoundHttpException
     */
    public function actionEditValue()
    {
        if (Yii::$app->request->isAjax) {
            $id = (int)unserialize(base64_decode(Yii::$app->request->post('pk')));
            if (!$config = Config::findOne($id)) {
                throw new NotFoundHttpException(Yii::t('app', 'Запрашиваемая страница не существует'));
            }
            $value = (string)Yii::$app->request->post('value');
            $config->value = $value;
            if ($config->validate()) {
                return $config->save();
            }

            return false;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'Запрашиваемая страница не существует'));
        }
    }

    /**
     * @param $id
     * @return Config
     * @throws NotFoundHttpException
     */
    protected function findModel($id): Config
    {
        if (($model = Config::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException(Yii::t('common', 'Запрашиваемая страница не существует'));
    }
}
