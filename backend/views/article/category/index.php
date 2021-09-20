<?php

use yii\helpers\Url;
use yii\helpers\Html;
use backend\widgets\grid\GridView;
use common\models\article\ArticleCategory;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend/article', 'Категории статей');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="adproperty-index x_panel">

    <div class="x_title">
        <h2><?= Html::encode($this->title) ?></h2>
        <ul class="nav navbar-right panel_toolbox">
            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
            <li><a class="close-link"><i class="fa fa-close"></i></a></li>
			<li><?= Html::a('<i class="fa fa-plus"></i>', ['create'], ['class' => 'create-item'])?></li>
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
                    'filter' => Html::a('<span><i class="fa fa-plus"></i>' . Yii::t('backend/article', 'Создать категорию') . '</span>', ['create'], ['class' => 'grid_button']),
                    'content' => function (ArticleCategory $model) {

                        $url_arr = [
                            ['url' => Url::to(['view', 'id' => $model->id]), 'label' => Yii::t('common', 'Смотреть')],
                            ['url' => Url::to(['update', 'id' => $model->id]), 'label' => Yii::t('common', 'Редактировать')],
                            ['url' => Url::to(['delete', 'id' => $model->id]), 'label' => Yii::t('common', 'Удалить'), 'method' => 'post'],
                        ];

                        return GridView::OperationsMenu($url_arr);
                    }
                ],
				[
					'headerOptions' => ['width' => '50'],
					'attribute' => 'id',
				],
				[
					'headerOptions' => ['width' => '140'],
					'attribute' => 'sort',
				],
				[
					'attribute' => 'name',
					'value' => function (ArticleCategory $model) {
						return Html::a(Html::encode($model->name), ['view', 'id' => $model->id]);
					},
					'format' => 'raw',
				],
            ],
        ]); ?>
    </div>
</div>
