<?php

namespace common\widgets\modal_ajax;

use yii\web\AssetBundle;

class ModalAjaxAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapAsset',
    ];

    /**
     * @inheritdoc
     */
    public $js = [
        'js/modal-ajax.js'
    ];

    /**
     * @inheritdoc
     */
    public $css = [
        'css/modal-colors.css',
    ];

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->sourcePath = __DIR__ . "/assets";
        parent::init();
    }
}
