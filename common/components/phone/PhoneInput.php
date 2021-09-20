<?php

namespace common\components\phone;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\InputWidget;

/**
 * Widget of the phone input
 * @package borales\extensions\phoneInput
 */
class PhoneInput extends InputWidget
{
	/** @var string HTML tag type of the widget input ("tel" by default) */
	public $htmlTagType = 'tel';
	/** @var array Default widget options of the HTML tag */
	public $defaultOptions = ['autocomplete' => "off", 'class' => 'form-control'];
	/**
	 * @link https://github.com/jackocnr/intl-tel-input#options More information about JS-widget options.
	 * @var array Options of the JS-widget
	 */
	public $jsOptions = [];
	
	/**
	 * @throws \yii\base\InvalidConfigException
	 */
	public function init()
	{
		parent::init();
		PhoneInputAsset::register($this->view);
		$id = ArrayHelper::getValue($this->options, 'id');
		//$jsOptions = $this->jsOptions ? Json::encode($this->jsOptions) : "";
		$jsInit = <<<JS
(function ($) {
    "use strict";
    var input = document.querySelector('#$id');
    var iti = intlTelInput(input, {
    	allowExtensions: true,
  		initialCountry: "auto",
  		//preferredCountries: ["ru"],
  		nationalMode: false,
  		//separateDialCode: true,
  		//formatOnDisplay: true,
		geoIpLookup: function(callback) {
			$.get('https://ipinfo.io', function() {}, "jsonp").always(function(resp) {
			  var countryCode = (resp && resp.country) ? resp.country : "";
			  callback(countryCode);
			});
		},
	});
    var handleChange = function() {
	  var number = iti.getNumber();
	  var format = iti.INTERNATIONAL;
	  iti.setNumber(number, format);
	};
    input.addEventListener('change', handleChange);
	input.addEventListener('keyup', handleChange);
})(jQuery);
JS;
		$this->view->registerJs($jsInit);
	}
	
	/**
	 * @return string
	 */
	public function run()
	{
		$options = ArrayHelper::merge($this->defaultOptions, $this->options);
		if ($this->hasModel()) {
			return Html::activeInput($this->htmlTagType, $this->model, $this->attribute, $options);
		}
		return Html::input($this->htmlTagType, $this->name, $this->value, $options);
	}
}
