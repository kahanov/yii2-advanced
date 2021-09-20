<?php

use yii\helpers\Url;
use yii\helpers\Html;
use backend\widgets\grid\GridView;
use common\models\Config;
use yii2mod\editable\EditableColumn;

/* @var $this yii\web\View */
/* @var $searchModel \backend\forms\settings\params\ParamsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Список параметров');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="x_panel">
    <div class="x_title">
        <h2><?= Html::encode($this->title) ?></h2>
        <ul class="nav navbar-right panel_toolbox">
            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
            <li><a class="close-link"><i class="fa fa-close"></i></a></li>
        </ul>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <div class="alert alert-success alert-dismissible " role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
            <strong><?= Yii::t('app', 'Параметры') ?></strong> <?= Yii::t('app', 'хранятся в базе данных.') ?>
            <?= Yii::t('app', 'Параметры в файлах params.php объединяются/заменяются с параметрами бд.') ?>
            <?= Yii::t('app', 'Использовать') ?> Yii::$app->params['param']
        </div>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'id' => 'griditems',
            'layout' => "\n{summary}\n{items}{pager}",
            'summary' => "<p>" . Yii::t('app', 'Показаны') . " {begin} - {end} " . Yii::t('app', 'из') . " {totalCount} " . Yii::t('app', 'записей') . "</p>",
            'tableOptions' => ['class' => 'table dataTable projects'],
            'bordered' => false,
            'striped' => true,
            'columns' => [
                [
                    'headerOptions' => ['width' => '200'],
                    'contentOptions' => ['class' => 'operations'],
                    'label' => Yii::t('app', 'Операции'),
                    'filter' => Html::a('<span><i class="fa fa-plus"></i>' . Yii::t('app', 'Создать параметр') . '</span>', ['create'], ['class' => 'grid_button']),
                    'content' => function (Config $model) {
                        $url_arr = [
                            ['url' => Url::to(['update', 'id' => $model->id]), 'label' => Yii::t('common', 'Редактировать')],
                            [
                                'url' => Url::to(['delete', 'id' => $model->id]),
                                'label' => Yii::t('common', 'Удалить'),
                                'method' => 'post',
                                'attr' => ['data-confirm' => Yii::t('common', 'Вы уверены, что хотите удалить этот параметр?')],
                            ],
                        ];

                        return GridView::OperationsMenu($url_arr);
                    }
                ],
                [
                    'attribute' => 'name',
                    'label' => Yii::t('app', 'Параметр'),
                ],
                [
                    'attribute' => 'desc',
                    'label' => Yii::t('app', 'Описание'),
                ],
                [
                    'attribute' => 'type',
                    'label' => Yii::t('app', 'Тип'),
                ],
                [
                    'attribute' => 'value',
                    'label' => Yii::t('app', 'Значение'),
                    'class' => EditableColumn::class,
                    'url' => ['edit-value'],
                    'editableOptions' => function (Config $model) {
                        $options = [
                            'value' => $model->value,
                        ];
                        if ($model->type == 'bool') {
                            $options['type'] = 'select';
                            $options['source'] = [
                                1,
                                0
                            ];
                        }

                        return $options;
                    },
                    'value' => function (Config $model) {
                        return $model->value . ' (' . Yii::t('app', 'Нажмите что бы изменить') . ')';
                    },
                    'format' => 'raw',
                ],
            ],
        ]); ?>
    </div>
</div>
