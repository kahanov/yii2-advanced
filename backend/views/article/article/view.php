<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model \common\models\article\Article */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend/article', 'Список статей'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="adproperty-view x_panel">

	<div class="x_title">
		<h2><?= Html::encode($this->title) ?></h2>
		<ul class="nav navbar-right panel_toolbox">
			<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
			</li>
			<li><a class="close-link"><i class="fa fa-close"></i></a></li>
			<li><?= Html::a('<i class="fa fa-plus"></i>', ['create'], ['class' => 'create-item']) ?></li>
		</ul>
		<div class="clearfix"></div>
	</div>

	<p>
		<?= Html::a(Yii::t('common', 'Редактировать'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
		<?= Html::a(Yii::t('common', 'Удалить'), ['delete', 'id' => $model->id], [
			'class' => 'btn btn-danger',
			'data' => [
				'confirm' => Yii::t('common', 'Вы уверены, что хотите удалить этот элемент?'),
				'method' => 'post',
			],
		]) ?>
	</p>
	<div class="x_content">
		<div class="col-md-12 col-lg-12 col-sm-12">
			<?= DetailView::widget([
				'model' => $model,
				'attributes' => [
					'id',
					'name',
					'slug',
					[
						'attribute' => 'status',
						'value' => \common\helpers\ArticleHelper::statusLabel($model->status),
						'format' => 'raw',
					],
					[
						'attribute' => 'category_id',
						'value' => ArrayHelper::getValue($model, 'category.name'),
					],
					[
						'attribute' => 'photo',
						'format' => ['image',['height'=>'100']],
						'value' => function ($model) {
							if ($photo = $model->photo) {
								return $photo->getThumbFileUrl('file', 'thumb');
							}
							return NULL;
						},
					],
					'title',
					'description:text',
				],
			]) ?>
		</div>
		<div class="box">
			<div class="box-header with-border"><?= Yii::t('backend/common', 'Содержание') ?></div>
			<div class="box-body">
				<?= Yii::$app->formatter->asHtml($model->content, [
					'Attr.AllowedRel' => array('nofollow'),
					'HTML.SafeObject' => true,
					'Output.FlashCompat' => true,
					'HTML.SafeIframe' => true,
					'URI.SafeIframeRegexp'=>'%^(https?:)?//(www\.youtube(?:-nocookie)?\.com/embed/|player\.vimeo\.com/video/)%',
				]) ?>
			</div>
		</div>
	</div>
</div>
