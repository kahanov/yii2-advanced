<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model \common\models\geo\Country */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend/geo', 'Страны'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="country-view x_panel">

    <div class="x_title">
        <h2><?= Html::encode($this->title) ?></h2>
        <ul class="nav navbar-right panel_toolbox">
            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
            <li><a class="close-link"><i class="fa fa-close"></i></a></li>
        </ul>
        <div class="clearfix"></div>
    </div>

    <p>
        <?= Html::a(Yii::t('common', 'Редактировать'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('common', 'Удалить'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('common', 'Вы уверены, что хотите удалить этот элемент?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <div class="x_content">
        <div class="col-md-12 col-lg-12 col-sm-12">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'id',
                    'title',
                    'v_title',
                    'slug',
                    'phone_code',
                    'currency_code',
                    'currency_name',
                    'language',
                ],
            ]) ?>
        </div>
    </div>
</div>
