<?php

namespace common\behaviors;

use PHPThumb\GD;
use yiidreamteam\upload\ImageUploadBehavior;
use yii\helpers\FileHelper;

class AvatarUploadBehavior extends ImageUploadBehavior
{
    /**
     * @throws \yii\base\Exception
     */
    public function createThumbs()
    {
        $path = $this->getUploadedFilePath($this->attribute);
        foreach ($this->thumbs as $profile => $config) {
            $thumbPath = static::getThumbFilePath($this->attribute, $profile);
            $state = false;
            if ($this->attribute == 'avatar') {
                if (is_file($path) && !is_file($thumbPath)) {
                    $state = true;
                }
            } else {
                if (!empty($this->owner->logotype)) {
                    $state = true;
                }
            }
            if ($state) {
                // setup image processor function
                if (isset($config['processor']) && is_callable($config['processor'])) {
                    $processor = $config['processor'];
                    unset($config['processor']);
                } else {
                    $processor = function (GD $thumb) use ($config) {
                        $thumb->adaptiveResize($config['width'], $config['height']);
                    };
                }

                $thumb = new GD($path, $config);
                call_user_func($processor, $thumb, $this->attribute, $this->owner);
                FileHelper::createDirectory(pathinfo($thumbPath, PATHINFO_DIRNAME), 0775, true);
                $thumb->save($thumbPath);
            }
        }
    }
}
