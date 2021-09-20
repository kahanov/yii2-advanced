<?php

namespace frontend\controllers\auth;

use Yii;
use yii\web\Controller;
use yii\web\BadRequestHttpException;
use common\forms\auth\ResetPasswordForm;
use common\services\auth\PasswordResetService;
use common\forms\auth\PasswordResetRequestForm;

class ResetController extends Controller
{
    private $service;

    /**
     * ResetController constructor.
     * @param $id
     * @param $module
     * @param PasswordResetService $service
     * @param array $config
     */
    public function __construct($id, $module, PasswordResetService $service, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
    }

    /**
     * @return string|\yii\web\Response
     * @throws \yii\base\Exception
     */
    public function actionRequest()
    {
        $form = new PasswordResetRequestForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->request($form);
                Yii::$app->session->setFlash('success', Yii::t('frontend/login', 'Проверьте электронную почту для получения дальнейших инструкций.'));

                return $this->goHome();
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('request', [
            'model' => $form,
        ]);
    }

    /**
     * @param $token
     * @return string|\yii\web\Response
     * @throws BadRequestHttpException
     * @throws \yii\base\Exception
     */
    public function actionConfirm($token)
    {
        try {
            $this->service->validateToken($token);
        } catch (\DomainException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        $form = new ResetPasswordForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->reset($token, $form);
                Yii::$app->session->setFlash('success', Yii::t('frontend/login', 'Новый пароль сохранен.'));
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }

            return $this->goHome();
        }

        return $this->render('confirm', [
            'model' => $form,
        ]);
    }
}
