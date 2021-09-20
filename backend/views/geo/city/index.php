<?php

use yii\helpers\Url;
use yii\helpers\Html;
use backend\widgets\grid\GridView;
use common\models\geo\City;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $model City */
/* @var $searchModel \backend\forms\geo\city\CitySearch */

$this->title = Yii::t('backend/geo', 'Поселения');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="city-index x_panel">

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
                    'filter' => Html::a('<span><i class="fa fa-plus"></i>' . Yii::t('backend/geo', 'Добавить поселение') . '</span>', Url::to(['create', 'country_id' => ($country_id) ? $country_id : NULL, 'region_id' => ($region_id) ? $region_id : NULL, 'district_id' => ($district_id) ? $district_id : NULL]), ['class' => 'grid_button']),
                    'content' => function ($model) {

                        $linksFalls = [
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
                [
                    'attribute' => 'country_id',
                    'value' => function (City $model) {
                        return $model->country->title;
                    },
                ],
                [
                    'attribute' => 'region_id',
                    'value' => function (City $model) {
                        return $model->region->title;
                    },
                ],
                [
                    'attribute' => 'district_id',
                    'value' => function (City $model) {
                        return ($model->district) ? $model->district->title : NULL;
                    },
                ],
            ],
        ]); ?>
    </div>
</div>
