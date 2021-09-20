<?php

namespace backend\widgets\grid;

use yii\grid\Column;
use yii\helpers\Html;

class GridView extends \yii\grid\GridView
{
    /**
     * @inheritdoc
     */
    public $dataColumnClass = 'backend\widgets\grid\DataColumn';

    /**
     * @inheritdoc
     */
    public $tableOptions = ['class' => 'table dataTable'];

    /**
     * @var bool whether to border grid cells
     */
    public $bordered = true;

    /**
     * @var bool whether to condense the grid
     */
    public $condensed = false;

    /**
     * @var bool whether to stripe the grid
     */
    public $striped = true;

    /**
     * @var bool whether to add a hover for grid rows
     */
    public $hover = false;

    public $filters_html = '';

    public $fixed_header = false;

    /**
     * @inheritdoc
     */
    public function init()
    {
        if ($this->bordered) {
            Html::addCssClass($this->tableOptions, 'table-bordered');
        }
        if ($this->condensed) {
            Html::addCssClass($this->tableOptions, 'table-condensed');
        }
        if ($this->striped) {
            Html::addCssClass($this->tableOptions, 'table-striped');
        }
        if ($this->hover) {
            Html::addCssClass($this->tableOptions, 'table-hover');
        }
        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        GridViewAsset::register($this->view);
        parent::run();
    }

    /**
     * @inheritdoc
     */
    public function renderPager()
    {
        return Html::tag('div', parent::renderPager(), ['class' => 'dataTables_paginate paging_simple_numbers']);
    }

    public function renderFilters()
    {
        $yourRows = '';
        if ($this->filters_html) {
            $yourRows = '<tr class="operate-head"><td colspan="1">' . $this->filters_html . '<td></tr>';
        }
        return parent::renderFilters() . $yourRows;
    }

    public function renderTableHeader()
    {
        $cells = [];
        foreach ($this->columns as $column) {
            /* @var $column Column */
            $cells[] = $column->renderHeaderCell();
        }
        $content = Html::tag('tr', implode('', $cells), $this->headerRowOptions);
        if ($this->filterPosition === self::FILTER_POS_HEADER) {
            $content = $this->renderFilters() . $content;
        } elseif ($this->filterPosition === self::FILTER_POS_BODY) {
            $content .= $this->renderFilters();
        }
        if ($this->fixed_header) {
            return "<thead class='fixed'>\n" . $content . "\n</thead>";
        } else {
            return "<thead>\n" . $content . "\n</thead>";
        }
    }

    /**
     * @param array $linksFalls
     * @param array $linksInline
     * @return null|string
     */
    public static function OperationsMenu($linksFalls = array(), $linksInline = array())
    {
        if (!empty($linksInline) || !empty($linksFalls)) {
            $html = '<div class="operations_menu">';
            if (!empty($linksInline)) {
                foreach ($linksInline as $linkInline) {
                    $label = $linkInline['label'];
                    $class = (!empty($linkInline['class'])) ? $linkInline['class'] : 'default';
                    $url = $linkInline['url'];
                    $options = ['class' => 'operations_menu__link--inline operations_menu__link--inline--' . $class];
                    if (isset($linkInline['method']) && !empty($linkInline['method'])) {
                        $options['data-method'] = $linkInline['method'];
                    }
                    if (isset($linkInline['attr']) && !empty($linkInline['attr'])) {
                        $options += $linkInline['attr'];
                    }
                    $icon = (isset($linkInline['icon_class'])) ? '<i class="fa fa-' . $linkInline['icon_class'] . '"></i>' : ''; // удаление = trash-o, редактирование = pencil-square-o, просмотр = list-alt
                    $html .= Html::a($icon . $label, $url, $options);
                }
            }

            if (!empty($linksFalls)) {
                $html .= '<div class="operations_menu__btn"><em class="operations_menu__name"><i class="operations_menu__icon fa fa-cog"></i>Операции<i class="operations_menu__arrow"></i></em><ul class="operations_menu__list">';

                foreach ($linksFalls as $link) {
                    $label = $link['label'];
                    $class = (isset($link['class'])) ? $link['class'] : '';

                    if (isset($link['child'])) {
                        $html .= '<li class="operations_menu__item"><div class="operations_menu__btn"><em class="operations_menu__name">' . $label . '<i class="operations_menu__arrow"></i></em><ul class="operations_menu__list">';
                        foreach ($link['child'] as $linkChild) {
                            $attr = (!empty($linkChild['attr'])) ? $linkChild['attr'] : [];
                            $method = (isset($link['method'])) ? $link['method'] : '';
                            $url = $linkChild['url'];
                            $labelChild = $linkChild['label'];
                            $classChild = (isset($linkChild['class'])) ? $linkChild['class'] : '';
                            $html .= '<li class="operations_menu__item">' . self::formatUrl($url, $labelChild, $method, $attr, $classChild) . '</li>';
                        }
                        $html .= '</ul></div></li>';
                    } else {
                        $attr = (!empty($link['attr'])) ? $link['attr'] : [];
                        $method = (isset($link['method'])) ? $link['method'] : '';
                        $url = $link['url'];
                        $html .= '<li class="operations_menu__item">' . self::formatUrl($url, $label, $method, $attr, $class) . '</li>';
                    }
                }

                $html .= '</ul></div>';
            }
            $html .= '</div>';
            return $html;
        }

        return null;
    }

    /**
     * @param $url
     * @param $label
     * @param string $method
     * @param array $attr
     * @param string $class
     * @return string
     */
    public static function formatUrl($url, $label, $method = '', array $attr, string $class = '')
    {
        $options = ['class' => 'operations_menu__link ' . $class];
        if (!empty($method)) {
            $options['data-method'] = $method;
        }
        if (!empty($attr)) {
            $options += $attr;
        }
        return Html::a($label, $url, $options);
    }
}
