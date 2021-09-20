<?php

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\DataProviderInterface */
/* @var $categoryName */

?>
<div class="article__content-container col-md-9">
    <div itemscope itemtype="https://schema.org/Article" class="articles">
			<span class="article__schema">
				<span itemprop="headline"><?= Yii::$app->name ?></span>
				<span itemprop="description"></span>
			</span>
        <div class="articles__header">
            <div class="articles__header-text">
                <h1 class="articles__header-title">
                    <?= $categoryName ?>
                </h1>
            </div>
            <div class="articles__header-search"></div>
        </div>
        <ul class="articles__list">
            <?= \yii\widgets\ListView::widget([
                'dataProvider' => $dataProvider,
                'layout' => "{items}\n{pager}",
                'itemView' => '_post',
            ]) ?>
        </ul>
    </div>
</div>

