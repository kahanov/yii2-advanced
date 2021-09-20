<?php

namespace common\widgets\maps;

use Yii;
use yii\base\Widget;
use yii\helpers\Json;
use yii\web\View;

class AddressSearchMap extends Widget
{
    public $language = 'ru_RU';
    public $apiKey = null;
    public $width = '100%';
    public $height = '400px';
    public $mapOptions = [];
    public $disableScroll = '';
    public $resultFunction = ''; // js function resultFunction(obj)
    public $mapHide = '';
    public $coordinates = '55.753215, 37.622504';
    public $formId = 'address-search-form';
    public $mapId = 'address-search-map';
    public $addressInput = 'address-input';
    public $errorMsg;
    public $errorHint = [];

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        parent::init();
        if ($this->apiKey === null) {
            $this->apiKey = Yii::$app->params['yandexApiKey'];
        }
        $this->mapOptions = Json::encode($this->mapOptions);
        $this->errorMsg = Yii::t('app', 'Неточный адрес:');
        $errorHint = [
            'range' => Yii::t('app', 'уточните номер дома'),
            'street' => Yii::t('app', 'уточните улицу или номер дома'),
            'default' => Yii::t('app', 'уточните адрес'),
        ];
        $this->errorHint = Json::encode($errorHint);
        $this->registerClientScript();
    }

    /**
     * @return string
     */
    public function run()
    {
        return $this->render('addressSearch', [
            'widget' => $this
        ]);
    }

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function registerClientScript()
    {
        $view = $this->getView();
        $bundle = AddressSearchAsset::register($view);
        $url = 'https://api-maps.yandex.ru/2.1/?lang=' . $this->language . '&apikey=' . $this->apiKey;
        Yii::$app->view->registerJsFile($url, ['position' => View::POS_END]);

        $js = <<< JS
            var mapHide = '$this->mapHide';
            if (mapHide === 'show') {
                ymaps.ready(init);
            } else {
                $('.map-edit').on('click', function (e) {
                    e.preventDefault();
                    $(this).remove();
                    $('.map-content').show();
                    ymaps.ready(init);
                });
            }
            function init() {
            	var suggestView = new ymaps.SuggestView('$this->addressInput'),
            		mapOptions = {$this->mapOptions},
            	 	map = new ymaps.Map("$this->mapId", mapOptions),
            	 	coordinates = "$this->coordinates",
            	 	disableScroll = '$this->disableScroll',
            	 	placemark,
            	 	value = $('#$this->addressInput').val(),
            	 	preloader = $('.map-block .block__preloader');
            	
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
				
				// Если есть компонент и адресс не введен
				if (navigator.geolocation && !value.length) {
				   // Отправляем запрос на получение местоположения
                  navigator.geolocation.getCurrentPosition(function(position) {
                      var latitude = position.coords.latitude,
                        longitude = position.coords.longitude;
                      coordinates = latitude + ',' + longitude;
                      // Если пользователь разрешил отображаем
                      geocode(coordinates);
                  });
                } else {
				    geocode(coordinates);
                }
				
				suggestView.events.add('select', function (e) {
					value = e.get('item').value;
					geocode(value);
				});
				
				$('#$this->addressInput').focusout(function(e) {
					value = this.value;
					if (suggestView.state.get('activeIndex') === null && value !== '') {
						geocode(value);
					}
  				});
				
				// Слушаем клик на карте.
                map.events.add('click', function (e) {
                    var coords = e.get('coords');
                    placemark.geometry.setCoordinates(coords);
                    getAddress(coords);
                });
                
                // Определяем адрес по координатам (обратное геокодирование).
                function getAddress(coords) {
                    placemark.properties.set('iconCaption', 'поиск...');
                    geocode(coords);
                }
				
				function geocode(request) {
					// Геокодируем введённые данные.
					ymaps.geocode(request).then(function (res) {
						var obj = res.geoObjects.get(0),
							errorMsg = '$this->errorMsg',
							errorHint = $this->errorHint,
							error;
			
						if (obj) {
							// Об оценке точности ответа геокодера можно прочитать тут: https://tech.yandex.ru/maps/doc/geocoder/desc/reference/precision-docpage/
							switch (obj.properties.get('metaDataProperty.GeocoderMetaData.precision')) {
								case 'exact':
									break;
								case 'number':
								case 'near':
								case 'range':
									error = errorMsg + ' ' + errorHint.range;
									break;
								/*case 'street':
								    //console.log(errorHint);
									error = errorMsg + ' ' + errorHint.street;
									break;*/
								case 'other':
								default:
									break;
							}
						} else {
							error = errorMsg + ' ' + errorHint.default;
						}
			
						// Если геокодер возвращает пустой массив или неточный результат, то показываем ошибку.
						if (error) {
							showError(error);
						} else {
							showResult(obj);
							getMetro(obj);
						}
					}, function (e) {
						console.log(e)
					})
			
				}
				
				function showResult(obj) {
					var mapContainer = $('#$this->mapId'),
						bounds = obj.properties.get('boundedBy'),
						// Рассчитываем видимую область для текущего положения пользователя.
						mapState = ymaps.util.bounds.getCenterAndZoom(
							bounds,
							[mapContainer.width(), mapContainer.height()]
						),
						// Сохраняем укороченный адрес для подписи метки.
						shortAddress = obj.getAddressLine(),
						resultFunction = '$this->resultFunction';
					
					$('#$this->addressInput').val(shortAddress);
					
					if (resultFunction != '') {
						window[resultFunction](obj, '$this->formId');
					}
					// Убираем контролы с карты.
					mapState.controls = [];
					
					// Создаём карту.
					createMap(mapState, shortAddress);
				}
				
				function getMetro(obj) {
				    $('.metro').hide();
				    $('#metro').html('');
					var coordinates = obj.geometry.getCoordinates().join(', ');
                        ymaps.geocode(coordinates, {
                          kind: 'metro',
                          results: 3
                        }).then(function (met) {
                            var metro = met.geoObjects;
                            var res = '';
                            met.geoObjects.each(function (obj, index) {
                                let multiRouteModel = new ymaps.multiRouter.MultiRouteModel([coordinates, obj.geometry.getCoordinates()], {
                                    routingMode: 'pedestrian'
                            }), multiRoute = new ymaps.multiRouter.MultiRoute(multiRouteModel);
                                map.geoObjects.add(multiRoute);
                                multiRouteModel.events.add("requestsuccess", function() {
                                    var route = multiRouteModel.getRoutes()[0],
                                    name = obj.properties.get('name'),
                                    distance = route.properties.get("distance").text,
                                    duration = route.properties.get("duration").text;
                                    var html = '<input type="hidden" name="AdCreateForm[metro][' + index + '][name]" value="' + name + '">';
                                    html += '<input type="hidden" name="AdCreateForm[metro][' + index + '][distance]" value="' + distance + '">';
                                    html += '<input type="hidden" name="AdCreateForm[metro][' + index + '][duration]" value="' + duration + '">';
                                    html += '<div class="metro__row">';
                                    html += '<div class="metro__content underground">';
                                    html += '<div class="metro__wrapper">';
                                    html += '<metro-icon ng-if="viewModel.underground.id" station-id="140" line-color="CF0000" class="icon-metro"><af-icon name="underground_1" ng-style="{ fill: metroIcon.color }" style="fill: rgb(207, 0, 0);"><svg width="16" height="11" viewBox="0 0 16 11" xmlns="http://www.w3.org/2000/svg" class="cui-icon cui-icon_underground_1"><path d="M11.154 0L8 5.53 4.844 0 1.1 9.466H0v1.428h5.657V9.466H4.81l.824-2.36L8 11l2.365-3.893.824 2.36h-.848v1.427H16V9.466h-1.1" fill-rule="evenodd"></path></svg></af-icon></metro-icon>';
                                    html += '<div class="metro__item metro__item--name"> ' + name + ' </div>';
                                    html += '</div>';
                                    html += '</div>';
                                    html += '<div class="metro__content time">';
                                    html += '<div class="metro__wrapper">';
                                    html += '<div class="metro__item"> ' + duration + ' </div>';
                                    html += '</div>';
                                    html += '</div>';
                                    html += '<div class="metro__content distance">';
                                    html += '<div class="metro__wrapper">';
                                    html += '<div class="metro__item"> ' + distance + ' </div>';
                                    html += '</div>';
                                    html += '</div>';
                                    html += '</div></div>';
                                    document.getElementById("metro").innerHTML += html;
                                    $('.metro').show();
                                });
                            });
                      });
				}
				
				function showError(message) {
				    preloader.hide();
					$('#$this->formId').yiiActiveForm('updateAttribute', '$this->addressInput', [message]);
				}
				
				function createMap(state, caption) {
					// Если карта еще не была создана, то создадим ее и добавим метку с адресом.
					if (!map) {
						map = new ymaps.Map('$this->mapId', state);
						placemark = new ymaps.Placemark(
							map.getCenter(), {
								iconCaption: caption,
								balloonContent: caption
							}, {
								preset: 'islands#redDotIconWithCaption'
							});
						map.geoObjects.add(placemark);
						// Если карта есть, то выставляем новый центр карты и меняем данные и позицию метки в соответствии с найденным адресом.
					} else {
						map.setCenter(state.center, state.zoom);
						placemark.geometry.setCoordinates(state.center);
						placemark.properties.set({iconCaption: caption, balloonContent: caption});
					}
				}
            }
JS;
        $view->registerJs($js);
    }
}
