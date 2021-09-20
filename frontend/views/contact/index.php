<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

/* @var $model frontend\forms\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

$this->title = Yii::t('frontend/site', 'Связаться с нами');
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="site-contact page">
        <div class="page__content">
            <h1><?= Html::encode($this->title) ?></h1>

            <p>
                <?= Yii::t('frontend/site', 'Если у вас есть каки либо вопросы, напишите нам используя форму ниже.') ?>
            </p>

            <div class="row">
                <div class="col-lg-5">
                    <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>

                    <?= $form->field($model, 'name')->textInput(['autofocus' => true]) ?>

                    <?= $form->field($model, 'email') ?>

                    <?= $form->field($model, 'subject') ?>

                    <?= $form->field($model, 'body')->textarea(['rows' => 6]) ?>

                    <?= $form->field($model, 'verifyCode')->widget(Captcha::class, [
                        'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div><span title="' . Yii::t('common', 'Обновить код') . '" class="сaptcha-refresh glyphicon glyphicon-refresh"></span></div>',
                    ]) ?>

                    <?= Html::activeHiddenInput($model, 'check', [
                        'id' => 'check',
                        'value' => '',
                    ]) ?>

                    <div class="form-group">
                        <?= Html::submitButton(Yii::t('common', 'Отправить'), ['class' => 'btn btn-primary', 'name' => 'contact-button', 'onclick' => "document.getElementById('check').value = 'nospam';"]) ?>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>

        </div>
    </div>
<?php $this->registerJs("
    $('.сaptcha-refresh').on('click', function(e){
        e.preventDefault();
        $('#contactform-verifycode-image').yiiCaptcha('refresh');
    })
"); ?>