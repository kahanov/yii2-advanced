<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/**
 * @var yii\web\View $this
 * @var backend\modules\yii2_admin\models\AuthItem $model
 */
$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rbac-admin', 'Rules'), 'url' => ['index']];
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
        <div class="auth-item-view">
            <h1><?= Html::encode($this->title) ?></h1>
            <p>
                <?= Html::a(Yii::t('rbac-admin', 'Update'), ['update', 'id' => $model->name], ['class' => 'btn btn-primary']) ?>
                <?= Html::a(Yii::t('rbac-admin', 'Delete'), ['delete', 'id' => $model->name], [
                    'class' => 'btn btn-danger',
                    'data-confirm' => Yii::t('rbac-admin', 'Are you sure to delete this item?'),
                    'data-method' => 'post',
                ]); ?>
            </p>
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'name',
                    'className',
                ],
            ]); ?>
        </div>
    </div>
</div>
