<?php

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\DataProviderInterface */

/* @var $searchForm \frontend\forms\CompanySearchForm */

use common\helpers\BaseFrontendHelper;
use frontend\assets\CompanyAsset;

CompanyAsset::register($this);

$this->title = Yii::t('company', 'Список компаний') . BaseFrontendHelper::pageString('page');
$this->registerMetaTag(['name' => 'description', 'content' => Yii::t('company', 'Список компаний: информация о компаниях, контакты')]);
?>
<?= $this->render('bloks/_search_form', [
    'searchForm' => $searchForm,
    'dataProvider' => $dataProvider,
]) ?>
<?= $this->render('bloks/_list', [
    'dataProvider' => $dataProvider,
    'searchForm' => $searchForm,
]) ?>


