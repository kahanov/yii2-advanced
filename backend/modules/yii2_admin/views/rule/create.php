<?php

use yii\helpers\Html;

/* @var $this  yii\web\View */
/* @var $model backend\modules\yii2_admin\models\BizRule */

$this->title = Yii::t('rbac-admin', 'Create Rule');
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
        <div class="auth-item-create">
            <h1><?= Html::encode($this->title) ?></h1>
            <?= $this->render('_form', [
                'model' => $model,
            ]); ?>
        </div>
    </div>
</div>
