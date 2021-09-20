<?php

namespace frontend\widgets\menu;

use frontend\widgets\menu\assets\NavBarAsset;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Json;

class NavBar extends Widget
{
	
	public $contain = null;
	/**
	 * <li class="name">
	 *     <h1><a href="#">My Site</a></h1>
	 * </li>
	 * @var array
	 */
	public $title = [
		'name' => '',
		'nameUrl' => '/',
		'nameOptions' => [],
		'listNameOptions' => []
	];
	/**
	 *
	 * @var array
	 */
	public $options = [
		'navOptions' => [],
		'titleAreaOptions' => [],
		'sectionOptions' => []
	];
	
	/**
	 * @var string
	 */
	public $view = "//widgets/topbar.twig";
	
	public function init()
	{
		parent::init();
		\Yii::setAlias('@topbar', dirname(__FILE__));
		$this->registerAsset();
		if (!is_null($this->contain))
			echo Html::beginTag('div', $this->contain);
		
		Html::addCssClass($this->title['nameOptions'], 'top-bar');
		Html::addCssClass($this->title['listNameOptions'], 'name');
		Html::addCssClass($this->options['sectionOptions'], 'top-bar-section');
		Html::addCssClass($this->options['navOptions'], 'top-bar');
		
		if (!isset($this->options['navOptions']['data-topbar']))
			$this->options['navOptions']['data-topbar'] = '';
		
		echo Html::beginTag('nav', $this->options['navOptions']);
		echo Html::beginTag('ul', ['class' => 'title-area']);
		if (!empty($this->title['name'])) {
			echo Html::beginTag('li', $this->title['listNameOptions']);
			echo Html::beginTag('h1');
			echo Html::a($this->title['name'], $this->title['nameUrl'], $this->title['nameOptions']);
			echo Html::endTag('h1');
			echo Html::endTag('li');
		}
		
		echo Html::tag('li', '<a href="#"><span>Menu</span></a>', ['class' => "toggle-topbar menu-icon"]);
		echo Html::endTag('ul');
		echo Html::beginTag('section', $this->options['sectionOptions']);
	}
	
	/**
	 * Renders the widget.
	 */
	public function run()
	{
		echo Html::endTag('section');
		echo Html::endTag('nav');
		if (!is_null($this->contain))
			echo Html::endTag('div');
	}
	
	private function registerAsset()
	{
		$view = $this->getView();
		NavBarAsset::register($view);
	}
}
