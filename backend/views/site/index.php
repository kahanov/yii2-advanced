<?php

use yii\helpers\Html;

/* @var $this yii\web\View */

$this->title = 'Главная страница админки';
?>
<div class="site-index x_panel">
    <div class="x_title">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <div class="x_content">
        <div class="row">
            <div class="col-lg-4">
                <h2><?= Html::encode($this->title) ?></h2>
                <p><?= Html::encode($this->title) ?></p>
            </div>
        </div>
    </div>
</div>
