<?php

namespace frontend\controllers;

use common\services\article\ArticleService;
use common\services\user\CompanyService;
use Yii;
use yii\web\Controller;

class SiteController extends Controller
{
    private $articleService;
    private $companyService;

    /**
     * SiteController constructor.
     * @param $id
     * @param $module
     * @param ArticleService $articleService
     * @param CompanyService $companyService
     * @param array $config
     */
    public function __construct(
        $id,
        $module,
        ArticleService $articleService,
        CompanyService $companyService,
        $config = []
    )
    {
        parent::__construct($id, $module, $config);
        $this->articleService = $articleService;
        $this->companyService = $companyService;
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * @return mixed
     */
    public function actionIndex()
    {
        $companies = $this->companyService->getHomeList();
        $articles = $this->articleService->getNews();

        return $this->render('index', [
            'articles' => $articles->getModels(),
            'companies' => $companies->getModels(),
        ]);
    }

    /**
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}
