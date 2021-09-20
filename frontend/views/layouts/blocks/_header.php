<?php

/* @var $this \yii\web\View */

use common\widgets\modal_ajax\ModalAjax;
use yii\helpers\Url;

?>
<header class="header">
    <?= $this->render('_top_menu') ?>
    <?= $this->render('_bottom_menu') ?>
</header>
<!-- mobile-menu -->
<div class="mobile-menu">
    <div role="button" class="mobile-menu__nav-btn">
        <span class="mobile-menu__nav--base"><?= Yii::t('common', 'Каталог') ?></span>
        <div class="mobile-menu__close">
            <div class="mobile-menu__close-icon">×</div>
        </div>
    </div>
    <div class="mobile-menu__viewport"></div>
</div>
<div class="main-overlay"></div>
<?= ModalAjax::widget([
    'header' => Yii::t('common/ad', 'Выберите регион'),
    'id' => 'modal_city',
    'size' => ModalAjax::SIZE_LARGE,
    'url' => Yii::$app->urlManager->createAbsoluteUrl(['/helper/choice-city']),
    'ajaxSubmit' => true,
    'autoClose' => true,
    //'footer' => Html::button(Yii::t('common', 'Применить'), ['class' => 'btn btn-default center-block submit-modal', 'data-dismiss' => 'modal']),
]); ?>
