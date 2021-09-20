<?php

/* @var $this yii\web\View */
/* @var $model \frontend\forms\ChoiceCityForm */
/* @var $regions */
/* @var $countryId */

use kartik\form\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

?>
<?php $form = ActiveForm::begin([
	'id' => 'choice-city-form-' . $countryId,
	'type' => ActiveForm::TYPE_HORIZONTAL,
]); ?>
<div class="choice-city__content">
	<?= Html::activeHiddenInput($model, 'url', ['value' => Url::previous()]) ?>
	<?= $form->field($model, 'region_id', ['template' => "<div class='col-md-12'>{input}</div>"])->
	label(false)->radioList(
		$regions,
		[
			'custom' => true,
			'inline' => true,
			'id' => 'custom-radio-list-inline',
			'item' => function ($index, $label, $name, $checked, $value) {
				$check = $checked ? ' checked="checked"' : '';
				return "<div class=\"custom-control custom-radio custom-control-inline\"><input class=\"custom-control-input\" id=\"custom-radio-list-inline-$value\" type=\"radio\" name=\"$name\" value=\"$value\"$check><label for=\"custom-radio-list-inline-$value\" class=\"custom-control-label\"> $label</label></div>";
			},
		]
	) ?>
</div>
<div class="choice-city__bottom">
	<?= Html::submitButton(Yii::t('common', 'Применить'), ['class' => 'center-block btn btn-default']) ?>
</div>
<?php ActiveForm::end() ?>
