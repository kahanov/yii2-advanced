<?php

namespace common\widgets\addressForm;

use yii\base\Widget;

class AddressMap extends Widget
{
    public $model;
    public $form;
    public $mapHide = NULL;


    public function init()
    {
        parent::init();
        $this->registerClientScript();
    }

    public function run()
    {
        return $this->render('address_map', [
            'widget' => $this,
            'form' => $this->form,
            'model' => $this->model,
            'mapHide' => $this->mapHide,
        ]);
    }

    public function registerClientScript()
    {
        $view = $this->getView();
        $bundle = AddressAsset::register($view);
    }
}
