<?php
/* @var $this yii\web\View */

use yii\helpers\Html;

?>
<footer>
    <div class="container">
        <p class="pull-left">
            &copy;<?= date('Y') ?> <?= Html::encode(Yii::$app->name) ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
    <div class="clearfix"></div>
</footer>
