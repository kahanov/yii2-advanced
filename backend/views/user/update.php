<?php

/* @var $this yii\web\View */
/* @var $model \common\models\user\User */

/* @var $model backend\forms\user\UserEditForm */

use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\switchinput\SwitchInput;

$this->title = Yii::t('backend/user', 'Редактирование пользователя') . ': ' . $user->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend/user', 'Пользователи'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $user->id, 'url' => ['view', 'id' => $user->id]];
$this->params['breadcrumbs'][] = Yii::t('backend/user', 'Редактирование');
?>
<div class="x_panel">
    <div class="x_title">
        <h2><?= Html::encode($this->title) ?></h2>
        <ul class="nav navbar-right panel_toolbox">
            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
            </li>
        </ul>
        <div class="clearfix"></div>
    </div>

    <div class="x_content">
        <div class="col-md-12 col-lg-12 col-sm-12">
            <?php $form = ActiveForm::begin([
                'options' => [
                    'class' => 'form-horizontal form-label-left',
                    'novalidate' => ''
                ],
                //'enableClientValidation' => false,
                'fieldConfig' => [
                    'options' => ['class' => 'item form-group'],
                    'template' => '{label}<div class="col-md-6 col-sm-6 col-xs-12">{input}{error}{hint}</div>',
                    'labelOptions' => ['class' => 'control-label col-md-3 col-sm-3 col-xs-12'],
                    'inputOptions' => ['class' => 'form-control col-md-7 col-xs-12'],
                ],
            ]); ?>

            <?= $form->
            field($model, 'username')->
            label(Yii::t('backend/user', 'Логин'))->
            textInput(['maxLength' => true]) ?>

            <?= $form->
            field($model, 'email')->
            label(Yii::t('backend/user', 'Email'))->
            textInput(['maxLength' => true]) ?>

            <?= $form->field($model, 'role')->
            label(Yii::t('backend/user', 'Роль'))->
            dropDownList(\common\helpers\UserHelper::rolesList()) ?>

            <?= $form->
            field($model, 'is_api')->
            label(Yii::t('backend/user', 'Разрешить доступ к api'))->
            widget(SwitchInput::class, [
                'pluginOptions' => [
                    'onText' => Yii::t('common', 'Да'),
                    'offText' => Yii::t('common', 'Нет'),
                ],
                'pluginEvents' => [
                    'switchChange.bootstrapSwitch' => 'function(event, state) {
                        if (state) {
                            $(".field-usereditform-api_key").show();
                        } else {
                            $(".field-usereditform-api_key").hide();
                        }
                    }',
                ],
            ]) ?>

            <?= $form->field($model, 'api_key', ['options' => ['style' => ($model->is_api === 1) ? '' : 'display: none;']])->
            label(Yii::t('backend/user', 'API ключь'))->
            hint('<a class="my-gray-btn" id="generate_api" href="JavaScript:void(0);">' . Yii::t('common', 'Генерировать API ключь') . '</a>')->
            textInput() ?>

            <div class="ln_solid"></div>
            <div class="form-group">
                <div class="col-md-6 col-md-offset-3">
                    <?= Html::submitButton(Yii::t('backend/user', 'Сохранить'), ['class' => 'btn btn-success']) ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
