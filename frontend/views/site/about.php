<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = Yii::t('frontend/site', 'О нас');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about page">
    <div class="page__content">
        <h1><?= Html::encode($this->title) ?></h1>
        <p>
            О нас
        </p>
    </div>
</div>
