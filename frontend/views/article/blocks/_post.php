<?php

/* @var $this yii\web\View */

/* @var $model \common\models\article\Article */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\StringHelper;

$url = Url::to(['article/view', 'id' => $model->id]);
?>

<li class="articles__item">
    <div class="articles__item-block" itemscope itemtype="http://schema.org/Article">
        <div class="articles__item-content" itemprop="articleBody">
            <a class="articles__item-title" itemprop="headline" href="<?= $url ?>"><?= Html::encode($model->name) ?></a>
            <div class="articles__item-description" itemprop="description">
                <?php if (!empty($model->anons)): ?>
                    <?= Yii::$app->formatter->asHtml($model->anons) ?>
                <?php else: ?>
                    <p><?= nl2br(StringHelper::truncateWords(strip_tags(Html::decode($model->content)), 30)) ?></p>
                <?php endif; ?>
            </div>
            <div class="articles__item-info">
                <span class="articles__item-date" itemprop="datePublished"><span
                            class="glyphicon glyphicon-calendar"></span> <?= Yii::$app->formatter->asDatetime($model->updated_at); ?></span>
            </div>
        </div>
        <?php if ($model->photo): ?>
            <a class="articles__item-link_img" href="<?= $url ?>">
                <img class="lazyload articles__item-img" src=""
                     data-src="<?= Html::encode($model->photo->getThumbFileUrl('file', 'article_list')) ?>"
                     alt="<?= Html::encode($model->name) ?>" itemprop="image">
            </a>
        <?php endif; ?>
    </div>
</li>


