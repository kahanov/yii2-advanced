<?php

use backend\modules\yii2_admin\AnimateAsset;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\YiiAsset;

/* @var $this yii\web\View */
/* @var $model backend\modules\yii2_admin\models\Assignment */
/* @var $fullnameField string */

$email = $model->{$emailField};
if (!empty($fullnameField)) {
    $email .= ' (' . ArrayHelper::getValue($model, $fullnameField) . ')';
}
$email = Html::encode($email);

$this->title = Yii::t('rbac-admin', 'Assignment') . ' : ' . $email;

$this->params['breadcrumbs'][] = ['label' => Yii::t('rbac-admin', 'Assignments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $email;

AnimateAsset::register($this);
YiiAsset::register($this);
$opts = Json::htmlEncode([
    'items' => $model->getItems(),
]);
$this->registerJs("var _opts = {$opts};");
$this->registerJs($this->render('_script.js'));
$animateIcon = ' <i class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></i>';
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
        <div class="assignment-index">
            <h1><?= $this->title; ?></h1>

            <div class="row">
                <div class="col-sm-5">
                    <input class="form-control search" data-target="available"
                           placeholder="<?= Yii::t('rbac-admin', 'Search for available'); ?>">
                    <select multiple size="20" class="form-control list" data-target="available">
                    </select>
                </div>
                <div class="col-sm-1">
                    <br><br>
                    <?= Html::a('&gt;&gt;' . $animateIcon, ['assign', 'id' => (string)$model->id], [
                        'class' => 'btn btn-success btn-assign',
                        'data-target' => 'available',
                        'title' => Yii::t('rbac-admin', 'Assign'),
                    ]); ?><br><br>
                    <?= Html::a('&lt;&lt;' . $animateIcon, ['revoke', 'id' => (string)$model->id], [
                        'class' => 'btn btn-danger btn-assign',
                        'data-target' => 'assigned',
                        'title' => Yii::t('rbac-admin', 'Remove'),
                    ]); ?>
                </div>
                <div class="col-sm-5">
                    <input class="form-control search" data-target="assigned"
                           placeholder="<?= Yii::t('rbac-admin', 'Search for assigned'); ?>">
                    <select multiple size="20" class="form-control list" data-target="assigned">
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>
