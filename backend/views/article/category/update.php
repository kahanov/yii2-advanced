<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $category \common\models\article\ArticleCategory */
/* @var $model \backend\forms\article\CategoryForm */

$this->title = Yii::t('backend/article', 'Редактирование категории') . ': ' . $category->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend/article', 'Список категорий'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $category->name, 'url' => ['view', 'id' => $category->id]];
$this->params['breadcrumbs'][] = Yii::t('common', 'Редактирование');
?>
<div class="adproperty-update x_panel">
    <div class="x_title">
        <h2><?= Html::encode($this->title) ?></h2>
        <ul class="nav navbar-right panel_toolbox">
            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
            <li><?= Html::a('<i class="fa fa-plus"></i>', ['create'], ['class' => 'create-item']) ?></li>
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
