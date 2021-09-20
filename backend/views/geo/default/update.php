<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $country \common\models\geo\Country */
/* @var $model \backend\forms\geo\country\CountryForm */

$this->title = Yii::t('backend/geo', 'Редактирование страны') . ': ' . $country->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend/geo', 'Страны'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $country->title, 'url' => ['view', 'id' => $country->id]];
$this->params['breadcrumbs'][] = Yii::t('common', 'Редактирование');
?>
<div class="country-update x_panel">
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
            ]) ?>
        </div>
    </div>
</div>
