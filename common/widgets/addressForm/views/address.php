<?php
/* @var $widget common\widgets\maps\AddressSearch */
/* @var $form kartik\form\ActiveForm */

/* @var $model \yii\base\Model */

use common\widgets\maps\AddressSearch;
use yii\helpers\Html;

?>
<?= $form->field($model, 'address')->label(false)->textInput([
	'placeholder' => Yii::t('common', 'Введите адрес...'),
]) ?>
<?= Html::activeHiddenInput($model, 'country_id', ['id' => 'country_id-input']); ?>
<?= Html::activeHiddenInput($model, 'region_id', ['id' => 'region_id-input']); ?>
<?= Html::activeHiddenInput($model, 'district_id', ['id' => 'district_id-input']); ?>
<?= Html::activeHiddenInput($model, 'city_id', ['id' => 'city_id-input']); ?>
<?= Html::activeHiddenInput($model, 'street_id', ['id' => 'street_id-input']); ?>
<?= Html::activeHiddenInput($model, 'house_number', ['id' => 'house_number-input']); ?>
<?= AddressSearch::widget([
	'formId' => 'item-search-form',
	'addressInput' => 'address',
	'countryInput' => 'country_id-input',
	'regionInput' => 'region_id-input',
	'districtInput' => 'district_id-input',
	'cityInput' => 'city_id-input',
    'streetIdInput' => 'street_id-input',
	'houseInput' => 'house_number-input',
]) ?>
