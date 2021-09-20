<?php

use yii\helpers\Html;

/* @var $this yii\web\View */

?>
<div class="col-md-3 article__categories">
	<nav class="article__categories-nav">
		<?php if (!empty($categoriesList)): ?>
			<ul class="article__categories-nav-list">
				<?php foreach ($categoriesList as $id => $name): ?>
					<li class="article__categories-nav-item <?= (!empty($this->params['active_category']) && $this->params['active_category'] == $id) ? 'article__categories-nav-item--active' : '' ?>">
						<?= Html::a(Html::encode($name), ['article/index', 'category_id' => $id], ['class' => 'article__categories-nav-link']) ?>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
	</nav>
</div>
