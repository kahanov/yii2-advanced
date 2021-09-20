<?php

namespace common\widgets\maps;

use Yii;
use yii\base\Widget;
use yii\helpers\Json;
use yii\web\View;

class Maps extends Widget
{

    public $language = 'ru_RU';
    public $apiKey = null;
    public $width = '100%';
    public $height = '400px';
    public $mapOptions = [];
    public $additionalOptions = ['searchControlProvider' => 'yandex#search'];
    public $disableScroll = '';
    public $mapId = 'map';
    public $coordinates;

    public function init()
    {
        parent::init();
        if ($this->apiKey === null) {
            $this->apiKey = Yii::$app->params['yandexApiKey'];
        }
        $this->mapOptions = Json::encode($this->mapOptions);
        $this->additionalOptions = Json::encode($this->additionalOptions);
        $this->registerClientScript();
    }

    public function run()
    {
        return $this->render('map', [
            'widget' => $this
        ]);
    }

    public function registerClientScript()
    {
        $view = $this->getView();
        $bundle = MapsAsset::register($view);
        $url = 'https://api-maps.yandex.ru/2.1/?lang=' . $this->language . '&apikey=' . $this->apiKey;
        //Yii::$app->view->registerJsFile($url, ['position' => View::POS_END]);
        $js = <<< JS
		    $.getScript('$url', function() {
                ymaps.ready(init);
            });
			
			function init() {
				var mapOptions = {$this->mapOptions},
				 	additionalOptions = {$this->additionalOptions},
            	 	map = new ymaps.Map("$this->mapId", mapOptions, additionalOptions),
            	 	disableScroll = '$this->disableScroll',
            	 	placemark;
				
				if (disableScroll != '') {
                    map.behaviors.disable('scrollZoom');
                }
                
				placemark = new ymaps.Placemark(mapOptions.center, {}, {
					// Опции.
					// Необходимо указать данный тип макета.
					iconLayout: 'default#imageWithContent',
					// Своё изображение иконки метки.
					iconImageHref: '$bundle->baseUrl/images/here.svg',
					// Размеры метки.
					iconImageSize: [48, 48],
					// Смещение левого верхнего угла иконки относительно
					// её "ножки" (точки привязки).
					iconImageOffset: [-24, -48],
					// Смещение слоя с содержимым относительно слоя с картинкой.
					iconContentOffset: [15, 15],
					// Макет содержимого.
					//iconContentLayout: MyIconContentLayout
				});
				
				map.geoObjects.add(placemark);
			}
JS;
        $view->registerJs($js, View::POS_END);
    }
}
