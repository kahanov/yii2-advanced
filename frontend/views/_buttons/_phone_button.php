<?php

/* @var $this yii\web\View */
/* @var $phone */

?>
<div class="phone_button">
    <div class="phone_button__container">
        <button class="phone_button__button_component"
            <?= (!empty($type) && !empty($id)) ? 'data-type="' . $type . '" data-id="' . $id . '"' : '' ?>
        >
			<span class="phone_button__component--text">
				<span class="phone_button__phone"><?= substr($phone, 0, -2) ?>
                    ..</span>
				<span class="phone_button__text"
                      style="display: none"><?= Yii::t('common', 'Показать телефон') ?></span>
			</span>
            <svg class="phone_button__preloader" viewBox="0 0 40 40" style="display: none; width: 16px; height: 16px;">
                <defs>
                    <linearGradient id="preloader-gradient" gradientUnits="userSpaceOnUse" y1="30" x2="40" y2="30">
                        <stop offset="0" stop-color="#000"></stop>
                        <stop offset="1" stop-color="#fff" stop-opacity="0"></stop>
                    </linearGradient>
                </defs>
                <path fill="url(#preloader-gradient)"
                      d="M20,35c-8.3,0-15-6.7-15-15H0c0,11,9,20,20,20c11,0,20-9,20-20h-5C35,28.3,28.3,35,20,35z"></path>
            </svg>
        </button>
    </div>
</div>
