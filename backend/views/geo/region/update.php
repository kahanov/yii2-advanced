<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $region \common\models\geo\Region */
/* @var $model \backend\forms\geo\region\RegionForm */

$this->title = Yii::t('backend/geo', 'Редактирование региона') . ': ' . $region->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend/geo', 'Регионы'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $region->title, 'url' => ['view', 'id' => $region->id]];
$this->params['breadcrumbs'][] = Yii::t('common', 'Редактирование');
?>
<div class="region-update x_panel">
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
