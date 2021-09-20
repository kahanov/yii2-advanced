<?php

namespace frontend\controllers\auth;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use common\forms\auth\SignupForm;
use common\services\auth\SignupService;

class SignupController extends Controller
{
    private $service;

    /**
     * SignupController constructor.
     * @param $id
     * @param $module
     * @param SignupService $service
     * @param array $config
     */
    public function __construct($id, $module, SignupService $service, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
    }

    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index'],
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @return string|\yii\web\Response
     * @throws \Throwable
     * @throws \yii\base\Exception
     */
    public function actionRequest()
    {
        if (Yii::$app->params['isSiteService']) {
            throw new \yii\web\ForbiddenHttpException(Yii::t('common', 'Извините, сейчас сайт переносится на новый сервер, по этому функционал этот заработает через несколько часов.'));
        }
        $form = new SignupForm();
        $form->scenario =  (php_sapi_name() === 'cli') ? 'default' : 'signup';
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                if ($form->check === 'nospam') {
                    $this->service->signup($form);
                    Yii::$app->session->setFlash('success', Yii::t('frontend/login', 'Проверьте электронную почту для получения дальнейших инструкций.'));

                    return $this->goHome();
                } else {
                    Yii::$app->session->setFlash('error', Yii::t('frontend/login', 'Доступ заблокирован'));
                }
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        } else {
            foreach ($form->getErrors() as $key => $value) {
                Yii::$app->session->setFlash('error', $value);
            }
        }

        return $this->render('request', [
            'model' => $form,
        ]);
    }

    /**
     * @param $token
     * @return mixed
     */
    public function actionConfirm($token)
    {
        try {
            $this->service->confirm($token);
            Yii::$app->session->setFlash('success', Yii::t('frontend/login', 'Ваша электронная почта подтверждена.'));

            return $this->redirect(['auth/auth/login']);
        } catch (\DomainException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }

        return $this->goHome();
    }
}
