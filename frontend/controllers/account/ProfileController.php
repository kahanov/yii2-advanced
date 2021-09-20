<?php

namespace frontend\controllers\account;

use frontend\forms\account\profile\AvatarForm;
use frontend\forms\account\profile\CompanyForm;
use frontend\forms\account\profile\LogotypeForm;
use frontend\forms\account\profile\UserProfileForm;
use Yii;
use common\models\user\User;
use frontend\controllers\account\controls\BaseController;
use common\services\user\ProfileService;
use yii\web\NotFoundHttpException;
use kartik\form\ActiveForm;
use yii\web\Response;

class ProfileController extends BaseController
{
    private $service;

    /**
     * ProfileController constructor.
     * @param $id
     * @param $module
     * @param ProfileService $service
     * @param array $config
     */
    public function __construct($id, $module, ProfileService $service, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
    }

    /**
     * @return array|string|Response
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     */
    public function actionIndex()
    {
        $userId = Yii::$app->user->id;
        $user = $this->findModel($userId);
        $profileType = 1;
        if (!empty(Yii::$app->request->get('profile_type'))) {
            $profileType = (int)Yii::$app->request->get('profile_type');
        }

        switch ($profileType) {
            case 2:
                if (empty($user->last_name) || empty($user->first_name)) {
                    Yii::$app->session->setFlash('info', Yii::t('account', 'Пожалуйста, заполните основной профиль для продолжения'));
                    return $this->redirect(['index']);
                }
                $company = $user->company;
                $form = new CompanyForm($user, $company);
                break;
            default:
                $form = new UserProfileForm($user);
        }
        if (Yii::$app->request->isPjax) {
            return $this->renderAjax('index', [
                'model' => $form,
                'user' => $user,
                'company' => (!empty($company)) ? $company : NULL,
            ]);
        }
        if ($form->load(Yii::$app->request->post())) {
            if (Yii::$app->request->isAjax) {
                session_write_close();
                Yii::$app->response->format = Response::FORMAT_JSON;

                return ActiveForm::validate($form);
            }
            if ($form->validate()) {
                try {
                    $this->service->edit($user, $form);

                    return $this->redirect(['index', 'profile_type' => $profileType]);
                } catch (\DomainException $e) {
                    Yii::$app->errorHandler->logException($e);
                    Yii::$app->session->setFlash('error', $e->getMessage());
                }
            }
        }

        return $this->render('index', [
            'model' => $form,
            'user' => $user,
            'company' => (!empty($company)) ? $company : NULL,
        ]);
    }

    /**
     * @throws NotFoundHttpException
     */
    public function actionAvatar()
    {
        $form = new AvatarForm();
        if (Yii::$app->request->isPost && Yii::$app->request->isAjax) {
            session_write_close();
            if ($form->load(Yii::$app->request->post()) && $form->validate()) {
                try {
                    $avatar = $this->service->uploadAvatar($form);
                    $result = [];
                    if (!empty($avatar)) {
                        $result = ['avatar' => $avatar];
                    }
                    Yii::$app->response->format = Response::FORMAT_JSON;

                    return $result;
                } catch (\DomainException $e) {
                    Yii::$app->errorHandler->logException($e);
                    Yii::$app->session->setFlash('error', $e->getMessage());
                }
            }
            if ($form->hasErrors()) {
                $result = [
                    'error' => $form->getFirstError('avatar')
                ];
                Yii::$app->response->format = Response::FORMAT_JSON;
                return $result;
            }
        } else {
            throw new NotFoundHttpException(Yii::t('common', 'Запрашиваемая страница не существует'));
        }
    }

    /**
     * @throws NotFoundHttpException
     */
    public function actionLogotype()
    {
        $form = new LogotypeForm();
        if (Yii::$app->request->isPost && Yii::$app->request->isAjax) {
            session_write_close();
            if ($form->load(Yii::$app->request->post()) && $form->validate()) {
                try {
                    $logotype = $this->service->uploadLogotype($form);
                    $result = [];
                    if (!empty($logotype)) {
                        $result = ['avatar' => $logotype];
                    }
                    Yii::$app->response->format = Response::FORMAT_JSON;

                    return $result;
                } catch (\DomainException $e) {
                    Yii::$app->errorHandler->logException($e);
                    Yii::$app->session->setFlash('error', $e->getMessage());
                }
            }
            if ($form->hasErrors()) {
                $result = [
                    'error' => $form->getFirstError('logotype')
                ];
                Yii::$app->response->format = Response::FORMAT_JSON;

                return $result;
            }
        } else {
            throw new NotFoundHttpException(Yii::t('common', 'Запрашиваемая страница не существует'));
        }
    }

    /**
     * @param $id
     * @return User|null
     * @throws NotFoundHttpException
     */
    protected function findModel(int $id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('common', 'Запрашиваемая страница не существует'));
    }
}
