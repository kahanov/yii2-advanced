<?php

namespace backend\controllers;

use common\helpers\BaseCommonHelper;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class HelperController extends Controller
{
	/**
	 * @return string
	 * @throws NotFoundHttpException
	 */
	public function actionTranslit()
	{
		if (Yii::$app->request->isAjax) {
			$string = Yii::$app->request->post('string');
			$slug = '';
			if (!empty($string)) {
				$slug = BaseCommonHelper::slugify($string);
			}
			Yii::$app->response->format = Response::FORMAT_JSON;

			return $slug;
		} else {
			throw new NotFoundHttpException(Yii::t('common', 'Запрашиваемая страница не существует'));
		}
	}
}
