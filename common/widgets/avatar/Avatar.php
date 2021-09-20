<?php

namespace common\widgets\avatar;

use common\widgets\avatar\Asset;
use yii;
use yii\base\InvalidConfigException;
use yii\helpers\Json;
use yii\widgets\InputWidget;

class Avatar extends InputWidget
{
    public $uploadParameter = 'avatar';
    public $width = 100;
    public $height = 100;
    public $uploadUrl;
    public $maxSize = 2097152; // 2MB
    public $thumbnailWidth = 300;
    public $thumbnailHeight = 300;
    public $cropAreaWidth = '100%';
    public $cropAreaHeight = '350px';
    public $extensions = 'jpeg, jpg, png, gif';
    public $onCompleteJcrop;
    public $avatarUrl;

    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();
        if ($this->uploadUrl === null) {
            throw new InvalidConfigException(Yii::t('avatar', 'Отсутствует параметр', ['attribute' => 'uploadUrl']));
        } else {
            $this->uploadUrl = Yii::getAlias($this->uploadUrl);
        }
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $this->registerClientAssets();
        return $this->render('widget', [
            'model' => $this->model,
            'widget' => $this
        ]);
    }

    public function registerClientAssets()
    {
        $view = $this->getView();
        Asset::register($view);
        $settings = [
            'url' => $this->uploadUrl,
            'name' => $this->uploadParameter,
            'maxSize' => $this->maxSize / 1024,
            'allowedExtensions' => explode(', ', $this->extensions),
            'size_error_text' => Yii::t('avatar', 'Ошибка размера файла', ['size' => $this->maxSize / (1024 * 1024)]),
            'ext_error_text' => Yii::t('avatar', 'Ошибка расширения файла', ['formats' => $this->extensions]),
            'accept' => 'image/*'
        ];
        if ($this->onCompleteJcrop)
            $settings['onCompleteJcrop'] = $this->onCompleteJcrop;
        $view->registerJs(
            'jQuery("#' . $this->options['id'] . '").parent().find(".new-photo-area").cropper(' . Json::encode($settings) . ', ' . $this->thumbnailWidth . ', ' . $this->thumbnailHeight . ');'
        );
    }
}
