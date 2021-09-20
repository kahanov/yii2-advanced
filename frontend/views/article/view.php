<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $model \common\models\article\Article */
/* @var $otherArticles array */

$this->title = (!empty($model->title)) ? Html::encode($model->title) : Html::encode($model->name);
$description = '';
if (!empty($model->description)) {
    $description = Html::encode($model->description);
} else {
    if (!empty($model->anons)) {
        $description = Html::encode(strip_tags($model->anons));
    } else {
        $description = StringHelper::truncate(Html::encode(strip_tags($model->content)), 250);
    }
}
$this->registerMetaTag(['name' => 'description', 'content' => $description]);

$this->params['breadcrumbs'][] = ['label' => Yii::t('frontend/article', 'Все статьи'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->category->name, 'url' => ['article/index', 'category_id' => $model->category->id]];
$this->params['breadcrumbs'][] = $model->name;

$this->params['active_category'] = $model->category->id;

$url = Url::to(['article/view', 'id' => $model->id]);

\frontend\assets\ArticleAsset::register($this);
?>
<?php
$css = <<<CSS
body, html {
  background-color: #fff!important;
}
CSS;
$this->registerCss($css);
?>
<div class="article">
    <?= $this->render('blocks/_category', [
        'categoriesList' => $model->categoriesList()
    ]) ?>
    <div class="article__content-container col-md-9">
        <article itemscope itemtype="http://schema.org/Article" class="post">
			<span class="article__schema">
				<span itemprop="publisher"><?= Yii::$app->name ?></span>
				<a role="button" href="<?= $url ?>" itemprop="url"></a>
			</span>
            <div>
                <h1 class="post__title" itemprop="headline"><?= Html::encode($model->name) ?></h1>
                <div class="post__block">
                    <div class="post__info" itemprop="hasPart" itemscope itemtype="http://schema.org/PublicationIssue">
						<span class="post__info-date" itemprop="datePublished">
							<span class="glyphicon glyphicon-calendar"></span> <?= Yii::$app->formatter->asDatetime($model->updated_at); ?>
						</span>
                    </div>
                    <div class="post__content">
                        <?php if ($photo = $model->photo): ?>
                            <div class="post__img-container">
                                <img class="post__img"
                                     src="<?= Html::encode($photo->getThumbFileUrl('file', 'origin')) ?>"
                                     alt="<?= Html::encode($model->name) ?>" itemprop="image">
                            </div>
                        <?php endif; ?>
                        <div class="post__text" itemprop="text">
                                <?= Yii::$app->formatter->asHtml($model->content, [
                                    'Attr.AllowedRel' => array('nofollow'),
                                    'HTML.SafeObject' => true,
                                    'Output.FlashCompat' => true,
                                    'HTML.SafeIframe' => true,
                                    'URI.SafeIframeRegexp' => '%^(https?:)?//(www\.youtube(?:-nocookie)?\.com/embed/|player\.vimeo\.com/video/)%',
                                ]) ?>
                        </div>
                    </div>
                </div>
            </div>
        </article>

        <?php if (!empty($otherArticles)): ?>
            <div class="articles" style="margin-top: 20px;">
                <div class="articles__header">
                    <div class="articles__header-text">
                        <h2 class="articles__header-title">
                            <?= Yii::t('frontend/article', 'Другие статьи') ?>
                        </h2>
                    </div>
                </div>
                <ul class="articles__list">
                    <?php foreach ($otherArticles as $otherArticle): ?>
                        <?= $this->render('blocks/_post', [
                            'model' => $otherArticle
                        ]) ?>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
    </div>
</div>

