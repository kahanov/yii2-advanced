<?php

/* @var $this yii\web\View */

/* @var $dataProvider yii\data\DataProviderInterface */

use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\helpers\Html;

?>
<div class="catalog panel panel-default">
    <div class="catalog__content panel-body">
        <div class="catalog__tabs">
            <div class="tabs-list">
                <a href="<?= Url::to(['/company']) ?>"
                   class="tab tab--active"><?= Yii::t('company', 'Все компании') ?></a>
                <a href="#" class="tab"><?= Yii::t('company', 'Item') ?></a>
            </div>
        </div>
        <div class="catalog__table-container">
            <div class="catalog__table-sort">
                <div class="col-md-7">
                    <div class="form-group input-group input-group-sm">
                        <label class="input-group-addon" for="input-sort"><?= Yii::t('common', 'Сортировать') ?>
                            :</label>
                        <select id="input-sort" class="form-control" onchange="location = this.value;">
                            <?php
                            $values = [
                                '' => Yii::t('common', 'По умолчанию'),
                                '-id' => Yii::t('common', 'Сначала новые'),
                                'id' => Yii::t('common', 'Сначала старые'),
                                'name' => Yii::t('common', 'Названия от А до Я'),
                                '-name' => Yii::t('common', 'Названия от Я до А'),
                            ];
                            $current = Yii::$app->request->get('sort');
                            ?>
                            <?php foreach ($values as $value => $label): ?>
                                <option value="<?= Html::encode(Url::current(['sort' => $value ?: null])) ?>"
                                        <?php if ($current == $value): ?>selected="selected"<?php endif; ?>><?= $label ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="form-group input-group input-group-sm">
                        <label class="input-group-addon" for="input-limit"><?= Yii::t('common', 'Количество') ?>
                            :</label>
                        <select id="input-limit" class="form-control" onchange="location = this.value;">
                            <?php
                            $values = [10, 15, 25, 50, 75, 100];
                            $current = $dataProvider->getPagination()->getPageSize();
                            ?>
                            <?php foreach ($values as $value): ?>
                                <option value="<?= Html::encode(Url::current(['per-page' => $value])) ?>"
                                        <?php if ($current == $value): ?>selected="selected"<?php endif; ?>><?= $value ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
            <table class="catalog__table">
                <tbody>
                <?php if (!empty($dataProvider->getModels())): ?>
                    <?php foreach ($dataProvider->getModels() as $company): ?>
                        <?= $this->render('_company', [
                            'company' => $company
                        ]) ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td>
                            <p class="catalog__not-found"><?= Yii::t('frontend', 'По вашему запросу ничего не найдено') ?></p>
                        </td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="col-sm-6 text-left">
    <?= LinkPager::widget([
        'pagination' => $dataProvider->getPagination(),
    ]) ?>
</div>
