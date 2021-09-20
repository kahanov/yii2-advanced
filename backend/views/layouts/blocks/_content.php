<?php
/* @var $this yii\web\View */

use yii\widgets\Breadcrumbs;
use common\widgets\Alert;
use yii\helpers\Html;

?>
<div class="right_col" role="main">
    <?php if (isset($this->params['h1'])): ?>
        <div class="page-title">
            <div class="title_left">
                <h1><?= $this->params['h1'] ?></h1>
            </div>
            <div class="title_right">
                <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search for...">
                        <span class="input-group-btn">
                                <button class="btn btn-default" type="button">Go!</button>
                            </span>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <div class="clearfix"></div>
    <?= Breadcrumbs::widget([
        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    ]) ?>
    <?= Alert::widget() ?>

    <?php if (!empty($this->title)) : ?>
    <!--<div class="page-title">
            <div class="title_left">
                <h3><?= Html::encode($this->title) ?></h3>
            </div>
            <div class="title_right">
                <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
                    <div class="input-group">
                        <input type="text" class="form-control"
                               placeholder="Введите для поиска...">
                        <span class="input-group-btn">
                                      <button class="btn btn-default"
                                              type="button">Поиск</button>
                                    </span>
                    </div>
                </div>
            </div>
        </div>-->
    <?php endif; ?>

    <?= $content ?>
</div>
