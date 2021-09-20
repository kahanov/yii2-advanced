<?php

use yii\helpers\Html;
use yii\bootstrap\Modal;

?>
<div class="avatar-widget">
    <?= Html::activeHiddenInput($model, $widget->attribute, ['class' => 'photo-field']); ?>
    <div class="avatar">
        <?php
        $src = '';
        $hideImg = 'hide';
        $hideAdd = '';
        if (!empty($widget->avatarUrl)) {
            $src = $widget->avatarUrl;
            $hideImg = '';
            $hideAdd = 'hide';
        }
        ?>
        <div class="avatar__container <?= (empty($src)) ? 'avatar__empty' : '' ?>">
            <div class="avatar__image-container <?= $hideImg ?>">
                <?= Html::img($src, [
                        'style' => 'height: ' . $widget->height . 'px; width: ' . $widget->width . 'px',
                        'class' => 'avatar__image'
                    ]
                ); ?>
            </div>
            <div class="avatar__add <?= $hideAdd ?>" style="display: block;">
                Добавить
            </div>
        </div>
        <div class="avatar__description">
            <div class="avatar__hint"><br><?= Yii::t('avatar', 'Рекомендованный') ?><br><?= Yii::t('avatar', 'размер
				для корректного') ?><br><?= Yii::t('avatar', 'отображения — 260 x 260 px') ?>
            </div>
        </div>
    </div>

    <?php Modal::begin([
        'header' => '<h4>' . Yii::t('avatar', 'Выберите фрагмент изображения') . '</h4>',
        'id' => 'cropper-modal',
        'size' => 'modal-lg',
        'footer' => Html::button(Yii::t('avatar', 'Сохранить'), ['class' => 'btn btn-sm btn-success crop-photo']),
    ]); ?>
    <div class='cropper-modal-content'>
        <div class="new-photo-area"
             style="height: <?= $widget->cropAreaHeight; ?>; width: <?= $widget->cropAreaWidth; ?>;"></div>
    </div>

    <?php Modal::end(); ?>
</div>
