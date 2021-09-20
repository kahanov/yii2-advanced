<?php

use yii\helpers\Url;
use yii\helpers\Html;
use backend\widgets\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend/geo', 'Страны');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="country-index x_panel">

    <div class="x_title">
        <h2><?= Html::encode($this->title) ?></h2>
        <ul class="nav navbar-right panel_toolbox">
            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
            </li>
            <li><a class="close-link"><i class="fa fa-close"></i></a></li>
        </ul>
        <div class="clearfix"></div>
    </div>

    <div class="x_content">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'layout' => "\n{summary}\n{items}\n{pager}",
            'summary' => "<p>" . Yii::t('backend/common', 'Показаны') . " {begin} - {end} " . Yii::t('backend/common', 'из') . " {totalCount} " . Yii::t('backend/common', 'записей') . "</p>",
            'tableOptions' => ['class' => 'table dataTable projects'],
            'bordered' => false,
            'striped' => true,
            'columns' => [
                [
                    'headerOptions' => ['width' => '200'],
                    'contentOptions' => ['class' => 'operations'],
                    'label' => Yii::t('common', 'Операции'),
                    'filter' => Html::a('<span><i class="fa fa-plus"></i>' . Yii::t('backend/geo', 'Добавить страну') . '</span>', ['create'], ['class' => 'grid_button']),
                    'content' => function ($model) {

                        $linksFalls = [
                            ['url' => Url::to(['/geo/region', 'country_id' => $model->id]), 'label' => Yii::t('backend/geo', 'Смотреть регионы')],
                            ['url' => Url::to(['/geo/city', 'country_id' => $model->id]), 'label' => Yii::t('backend/geo', 'Смотреть поселения')],
                            ['url' => Url::to(['update', 'id' => $model->id]), 'label' => Yii::t('common', 'Редактировать')],
                            ['url' => Url::to(['delete', 'id' => $model->id]), 'label' => Yii::t('common', 'Удалить'), 'method' => 'post'],
                        ];

                        $linksInline = [
                            [
                                'url' => Url::to(['view', 'id' => $model->id]),
                                'label' => Yii::t('common', 'Смотреть'),
                                'icon_class' => 'list-alt',
                                'class' => 'green'
                            ],
                        ];

                        return GridView::OperationsMenu($linksFalls, $linksInline);
                    }
                ],
                'id',
                'title',
                'phone_code',
                'language',
            ],
        ]); ?>
    </div>
</div>
