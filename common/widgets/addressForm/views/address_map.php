<?php
/* @var $widget common\widgets\maps\AddressSearchMap */
/* @var $form kartik\form\ActiveForm */
/* @var $model \yii\base\Model */
/* @var $mapHide */

use common\widgets\maps\AddressSearchMap;
use yii\helpers\Html;
?>
<?= $form->field($model, 'address', ['wrapperOptions' => ['class' => 'col-lg-12']])->label(false)->textInput(['autocomplete' => 'off']) ?>
<?= Html::activeHiddenInput($model, 'country_id', ['id' => 'country_id-input']); ?>
<?= Html::activeHiddenInput($model, 'region_id', ['id' => 'region_id-input']); ?>
<?= Html::activeHiddenInput($model, 'district_id', ['id' => 'district_id-input']); ?>
<?= Html::activeHiddenInput($model, 'city_id', ['id' => 'city_id-input']); ?>
<?= Html::activeHiddenInput($model, 'street_id', ['id' => 'street_id-input']); ?>
<?= Html::activeHiddenInput($model, 'house_number', ['id' => 'house_number-input']); ?>
<?= Html::activeHiddenInput($model, 'coordinates', ['id' => 'coordinates-input']); ?>
<?php
$center = (!empty($model->coordinates)) ? explode(',', $model->coordinates) : [55.753215, 37.622504];
$coordinates = (!empty($model->coordinates)) ? $model->coordinates : '55.753215, 37.622504';
?>
<?= AddressSearchMap::widget([
	'mapId' => 'address-search-map',
	'formId' => 'profile-form',
	'addressInput' => 'address',
	'resultFunction' => 'resultFunction',
	'mapHide' => ($mapHide) ? 'hide' : 'show',
    'coordinates' => $coordinates,
	'mapOptions' => [
		'center' => $center,
		'zoom' => 9,
		'controls' => ['zoomControl'],
		'control' => [],
	],
	'disableScroll' => 'true',
]) ?>
<div class="metro" style="display: none">
    <div class="metro__headers">
        <span class="underground"><?= Yii::t('app', 'Ближайшее метро') ?></span>
        <span class="time"><?= Yii::t('app', 'Времени до метро пешком') ?></span>
        <span class="distance"><?= Yii::t('app', 'Расстояние') ?></span>
    </div>
    <div id="metro"></div>
</div>
