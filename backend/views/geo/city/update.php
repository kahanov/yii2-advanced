<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $city \common\models\geo\City */
/* @var $model \backend\forms\geo\city\CityForm */

$this->title = Yii::t('backend/geo', 'Редактирование поселения') . ': ' . $city->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend/geo', 'Поселения'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $city->title, 'url' => ['view', 'id' => $city->id]];
$this->params['breadcrumbs'][] = Yii::t('common', 'Редактирование');
?>
<div class="city-update x_panel">
    <div class="x_title">
        <h2><?= Html::encode($this->title) ?></h2>
        <ul class="nav navbar-right panel_toolbox">
            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
        </ul>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <div class="col-md-12 col-lg-12 col-sm-12">
            <?= $this->render('_form', [
                'model' => $model,
                'city' => $city,
            ]) ?>
        </div>
    </div>
</div>
