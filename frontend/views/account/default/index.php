<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\authclient\widgets\AuthChoice;

$this->title = Yii::t('frontend/account', 'Личный кабинет пользователя');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cabinet-index page">
    <div class="page__content">
        <h1><?= Html::encode($this->title) ?></h1>
        <h3><?= Yii::t('frontend/account', 'Связать с соц. сетями') ?></h3>
        <?= AuthChoice::widget([
            'baseAuthUrl' => ['account/network/attach'],
        ]); ?>
    </div>
</div>
