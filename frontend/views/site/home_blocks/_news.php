<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $articles array */

?>
<div class="page news">
    <div class="page__content">
        <h2 class="page__title-home">
            <?= Yii::t('frontend', 'Новости') ?>
        </h2>
        <div class="page__delimiter"></div>
        <div class="news__container">
            <?php /** @var \common\models\article\Article $article */
            foreach ($articles as $article): ?>
                <?php $url = Url::to(['article/view', 'id' => $article->id]); ?>
                <div class="news__item">
                    <?php if ($article->photo): ?>
                        <a class="news__item-link_img" href="<?= $url ?>">
                            <img class="lazyload news__item-img" src="/images/loading.gif"
                                 data-src="<?= Html::encode($article->photo->getThumbFileUrl('file', 'article_list')) ?>"
                                 alt="<?= Html::encode($article->name) ?>">
                        </a>
                    <?php endif; ?>
                    <a href="<?= $url ?>" class="news__item-title"><?= Html::encode($article->name) ?></a>
                    <div class="news__item-description">
                        <?php if (!empty($article->anons)): ?>
                            <p><?= Yii::$app->formatter->asHtml($article->anons) ?></p>
                        <?php else: ?>
                            <p><?= StringHelper::truncateWords(Yii::$app->formatter->asHtml($article->content), 30) ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
            <?= Html::a(Yii::t('frontend', 'Все новости'), ['/article/news'], ['class' => 'home__view-all']) ?>
        </div>
    </div>
</div>
