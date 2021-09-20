<?php

namespace frontend\controllers\auth;

use Yii;
use yii\web\Controller;
use yii\authclient\AuthAction;
use yii\authclient\ClientInterface;
use common\services\auth\NetworkService;

class NetworkController extends Controller
{
    private $service;

    /**
     * NetworkController constructor.
     * @param $id
     * @param $module
     * @param NetworkService $service
     * @param array $config
     */
    public function __construct($id, $module, NetworkService $service, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
    }

    /**
     * @param $action AuthAction
     * @return bool
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {
        $hostInfo = Yii::$app->urlManager->hostInfo;
        $frontendHostInfo = Yii::$app->params['frontendHostInfo'];
        if (Yii::$app->controller->action->id == 'auth') {
            if ($hostInfo != $frontendHostInfo) {
                $session = Yii::$app->session;
                if (!$session->isActive) {
                    $session->open();
                }
                $session->set('auth_network_host', $hostInfo);
                $url = Yii::$app->request->getUrl();

                return $this->redirect($frontendHostInfo . $url)->send();
            }
        }
        if (Yii::$app->session->get('auth_network_host')) {
            $successUrl = $action->getSuccessUrl();
            $action->setSuccessUrl(Yii::$app->session->get('auth_network_host') . $successUrl);
        }

        return parent::beforeAction($action);
    }

    /**
     * @return array
     */
    public function actions()
    {
        return [
            'auth' => [
                'class' => AuthAction::class,
                'successCallback' => [$this, 'onAuthSuccess'],
            ],
        ];
    }

    /**
     * @param ClientInterface $client
     * @throws \Throwable
     * @throws \yii\base\Exception
     */
    public function onAuthSuccess(ClientInterface $client)
    {
        $network = $client->getId();
        $attributes = $client->getUserAttributes();
        try {
            $user = $this->service->auth($network, $attributes);
            if (!$user) {
                Yii::$app->session->setFlash('error', Yii::t('user', 'Не удалось получить E-mail'));
            } else {
                Yii::$app->user->login($user, Yii::$app->params['user.rememberMeDuration']);
            }
        } catch (\DomainException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
    }
}
