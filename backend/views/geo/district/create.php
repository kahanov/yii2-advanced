<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model \backend\forms\geo\district\DistrictForm */

$this->title = Yii::t('backend/geo', 'Добавление района');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend/geo', 'Районы'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="district-create x_panel">
    <div class="x_title">
        <h2><?= Html::encode($this->title) ?></h2>
        <ul class="nav navbar-right panel_toolbox">
            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
            </li>
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
