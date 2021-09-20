<?php

/* @var $this \yii\web\View */

/* @var $categoryMenuItems array */

?>
<ul class="menu-realty <?= (isset($isParent)) ? 'mobile-menu__realty-parent-list' : '' ?>">
    <?php foreach ($categoryMenuItems as $menu): ?>
        <?php
        $isParent = (!empty($menu['items'])) ? true : NULL;
        $url = $menu['url'];
        ?>
        <li>
            <a <?= (!$isParent) ? 'href="' . $url . '"' : '' ?>
                    class="mobile-menu__item mobile-menu__item--realty <?= ($isParent) ? 'mobile-menu__item--parent mobile-menu__item--parent-realty' : '' ?>">
                <?php if ($isParent): ?>
                    <div class="mobile-menu__open_category" data-href="<?= $url ?>"
                         title="<?= Yii::t('common', 'Перейти в категорию') ?>">
                        <div class="glyphicon glyphicon-link"></div>
                    </div>
                <?php endif; ?>
                <?= $menu['label'] ?>
                <div class="glyphicon glyphicon-menu-right"></div>
            </a>
            <?php if ($isParent): ?>
                <?= $this->render('__mobile-menu-categories', ['categoryMenuItems' => $menu['items'], 'isParent' => $isParent]) ?>
            <?php endif; ?>
        </li>
    <?php endforeach; ?>
</ul>
