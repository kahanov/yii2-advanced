<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $metro \common\models\geo\Metro */
/* @var $model \backend\forms\geo\metro\MetroForm */

$this->title = Yii::t('backend/geo', 'Редактирование метро') . ': ' . $metro->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend/geo', 'Список метро'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $metro->title, 'url' => ['view', 'id' => $metro->id]];
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
                'metro' => $metro,
            ]) ?>
        </div>
    </div>
</div>
