<?php

use yii\helpers\Url;
use yii\helpers\Html;
use common\models\user\User;
use common\helpers\UserHelper;
use kartik\date\DatePicker;
use backend\widgets\grid\GridView;
use yii\rbac\Item;

/* @var $this yii\web\View */
/* @var $searchModel backend\forms\user\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = Yii::t('backend/user', 'Пользователи');
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
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'layout' => "\n{summary}\n{items}{pager}",
            'summary' => "<p>" . Yii::t('backend/common', 'Показаны') . " {begin} - {end} " . Yii::t('backend/common', 'из') . " {totalCount} " . Yii::t('backend/common', 'записей') . "</p>",
            'tableOptions' => ['class' => 'table dataTable projects'],
            'bordered' => false,
            'striped' => true,
            'columns' => [
                [
                    'headerOptions' => ['width' => '200'],
                    'contentOptions' => ['class' => 'operations'],
                    'label' => Yii::t('common', 'Операции'),
                    'filter' => Html::a('<span><i class="fa fa-plus"></i>' . Yii::t('backend/user', 'Создать пользователя') . '</span>', ['create'], ['class' => 'grid_button']),
                    'content' => function (User $model) {
                        $urls = [
                            ['url' => Url::to(['view', 'id' => $model->id]), 'label' => Yii::t('common', 'Смотреть')],
                            ['url' => Url::to(['update', 'id' => $model->id]), 'label' => Yii::t('common', 'Редактировать')],
                            ['url' => Url::to(['delete', 'id' => $model->id]), 'label' => Yii::t('common', 'Удалить'), 'method' => 'post'],
                        ];

                        return GridView::OperationsMenu($urls);
                    }
                ],
                'id',
                [
                    'attribute' => 'created_at',
                    'label' => Yii::t('backend/user', 'Дата регистрации'),
                    'filter' => DatePicker::widget([
                        'model' => $searchModel,
                        'attribute' => 'date_from',
                        'attribute2' => 'date_to',
                        'type' => DatePicker::TYPE_RANGE,
                        'separator' => '-',
                        'pluginOptions' => [
                            'todayHighlight' => true,
                            'autoclose' => true,
                            'format' => 'yyyy-mm-dd',
                        ],
                    ]),
                    'format' => 'datetime',
                ],
                [
                    'attribute' => 'username',
                    'label' => Yii::t('backend/user', 'Логин'),
                    'value' => function (User $model) {
                        return Html::a(Html::encode($model->username), ['view', 'id' => $model->id]);
                    },
                    'format' => 'raw',
                ],
                'email:email',
                [
                    'attribute' => 'role',
                    'label' => Yii::t('backend/user', 'Роль'),
                    'filter' => UserHelper::rolesList(),
                    'value' => function (User $model) {
                        $roles = Yii::$app->authManager->getRolesByUser($model->id);
                        return $roles === [] ? $this->grid->emptyCell : implode(', ', array_map(function (Item $role) {
                            return UserHelper::roleLabel($role);
                        }, $roles));
                    },
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'status',
                    'label' => Yii::t('backend/user', 'Статус'),
                    'filter' => UserHelper::statusList(),
                    'value' => function (User $model) {
                        return UserHelper::statusLabel($model->status);
                    },
                    'format' => 'raw',
                ],
            ],
        ]); ?>
    </div>
</div>
