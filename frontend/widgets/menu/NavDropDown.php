<?php

namespace frontend\widgets\menu;

use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\base\Widget;

class NavDropDown extends Widget
{
    /**
     * @var array list of menu items in the dropdown. Each array element can be either an HTML string,
     * or an array representing a single menu with the following structure:
     *
     * - label: string, required, the label of the item link
     * - url: string, optional, the url of the item link. Defaults to "#".
     * - visible: boolean, optional, whether this menu item is visible. Defaults to true.
     * - linkOptions: array, optional, the HTML attributes of the item link.
     * - options: array, optional, the HTML attributes of the item.
     *
     * To insert divider use `<li role="presentation" class="divider"></li>`.
     */
    public $items = [];
    /**
     * @var boolean whether the labels for header items should be HTML-encoded.
     */
    public $encodeLabels = true;

    public $options = [];

    /**
     * Initializes the widget.
     * If you override this method, make sure you call the parent implementation first.
     */
    public function init()
    {
        parent::init();

        Html::addCssClass($this->options, 'dropdown-menu dropdown');
    }

    /**
     * Renders the widget.
     */
    public function run()
    {
        echo $this->renderItems($this->items);
    }


    /**
     * Renders menu items.
     * @param array $items the menu items to be rendered
     * @return string the rendering result.
     * @throws InvalidConfigException if the label option is not specified in one of the items.
     */
    protected function renderItems($items)
    {
        $lines = [];
        foreach ($items as $i => $item) {
            if (isset($item['visible']) && !$item['visible']) {
                unset($items[$i]);
                continue;
            }

            if (is_string($item)) {
                $lines[] = $item;
                continue;
            }


            if (!isset($item['label'])) {
                throw new InvalidConfigException("The 'label' option is required.");
            }

            $label = $this->encodeLabels ? Html::encode($item['label']) : $item['label'];
            $options = ArrayHelper::getValue($item, 'options', []);
            $linkOptions = ArrayHelper::getValue($item, 'linkOptions', []);
            $linkOptions['tabindex'] = '-1';

            $content = Html::a($label, ArrayHelper::getValue($item, 'url', '#'), $linkOptions);


            if (isset($item['items'])) {
                Html::addCssClass($options, 'has-dropdown');
                $content .= NavDropDown::widget(
                    [
                        'items' => $item['items']
                    ]
                );
            }
            $lines[] = Html::tag('li', $content, $options);
        }

        return Html::tag('ul', implode("\n", $lines), $this->options);
    }
}
