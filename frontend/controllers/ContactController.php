<?php

namespace frontend\controllers;

use Jaybizzle\CrawlerDetect\CrawlerDetect;
use Yii;
use yii\web\Controller;
use frontend\forms\ContactForm;
use common\services\ContactService;

class ContactController extends Controller
{
    private $service;

    /**
     * ContactController constructor.
     * @param $id
     * @param $module
     * @param ContactService $service
     * @param array $config
     */
    public function __construct($id, $module, ContactService $service, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionIndex()
    {
        $form = new ContactForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $crawlerDetect = new CrawlerDetect();
                // Если не тестирование и если бот
                if (php_sapi_name() !== 'cli' && $crawlerDetect->isCrawler()) {
                    Yii::$app->session->setFlash('error', Yii::t('frontend/site', 'Доступ заблокирован'));
                } else {
                    if ($form->check === 'nospam') {
                        $this->service->send($form);
                        Yii::$app->session->setFlash('success', Yii::t('frontend/site', 'Ваше сообщение успешно отправлено'));
                        return $this->goHome();
                    } else {
                        Yii::$app->session->setFlash('error', Yii::t('frontend/site', 'Доступ заблокирован'));
                    }
                }
            } catch (\Exception $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', Yii::t('frontend/site', 'Не удалось отправить сообщение'));
            }
            return $this->refresh();
        }

        return $this->render('index', [
            'model' => $form,
        ]);
    }
}
