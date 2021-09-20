<?php

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\DataProviderInterface */
/* @var $searchForm \frontend\forms\ArticleSearch */

/* @var $category \common\models\article\ArticleCategory */

use common\helpers\BaseFrontendHelper;

$category = $searchForm->getCategory();

$this->title = Yii::t('frontend/article', 'Все статьи') . BaseFrontendHelper::pageString('page');
Yii::$app->view->registerMetaTag(['name' => 'description', 'content' => 'description']);
if ($category) {
    $this->title = (!empty($category->title)) ? $category->title : $category->name;
    $this->title .= BaseFrontendHelper::pageString('page');
    $this->registerMetaTag(['name' => 'description', 'content' => $category->description]);
    $categoryName = $category->name;
    $this->params['breadcrumbs'][] = ['label' => Yii::t('frontend/article', 'Все статьи'), 'url' => ['index']];
    $this->params['active_category'] = $category->id;
}
$this->params['breadcrumbs'][] = (!empty($categoryName)) ? $categoryName : $this->title;

\frontend\assets\ArticleAsset::register($this);

$css = <<<CSS
body, html {
  background-color: #fff!important;
}
CSS;
$this->registerCss($css);
?>
<div class="article">
    <?= $this->render('blocks/_category', [
        'categoriesList' => $searchForm->categoriesList()
    ]) ?>

    <?= $this->render('blocks/_list', [
        'dataProvider' => $dataProvider,
        'categoryName' => (!empty($categoryName)) ? $categoryName : $this->title
    ]) ?>
</div>
