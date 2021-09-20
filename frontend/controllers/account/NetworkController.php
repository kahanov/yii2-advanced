<?php

namespace frontend\controllers\account;

use Yii;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\authclient\AuthAction;
use yii\authclient\ClientInterface;
use common\services\auth\NetworkService;
use frontend\controllers\account\controls\BaseController;

class NetworkController extends BaseController
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

    public function actions()
    {
        return [
            'attach' => [
                'class' => AuthAction::class,
                'successCallback' => [$this, 'onAuthSuccess'],
                'successUrl' => Url::to(['account/default/index']),
            ],
        ];
    }

    /**
     * @param ClientInterface $client
     * @throws \Exception
     */
    public function onAuthSuccess(ClientInterface $client): void
    {
        $network = $client->getId();
        $attributes = $client->getUserAttributes();
        $identity = ArrayHelper::getValue($attributes, 'id');
        try {
            $this->service->attach(Yii::$app->user->id, $network, $identity);
            Yii::$app->session->setFlash('success', Yii::t('user', 'Аккаунт успешно привязан'));
        } catch (\DomainException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
    }
}
