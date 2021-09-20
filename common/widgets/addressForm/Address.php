<?php

namespace common\widgets\addressForm;

use yii\base\Widget;

class Address extends Widget
{
    public $model;
    public $form;


    public function init()
    {
        parent::init();
        $this->registerClientScript();
    }

    public function run()
    {
        return $this->render('address', [
            'widget' => $this,
            'form' => $this->form,
            'model' => $this->model,
        ]);
    }

    public function registerClientScript()
    {
        $view = $this->getView();
        $bundle = AddressAsset::register($view);
    }
}
