<?php

namespace common\services;

use PHPThumb\GD;

class ImageCrop
{
    private $width;
    private $height;

    /**
     * ImageCrop constructor.
     * @param $width
     * @param $height
     */
    public function __construct($width, $height)
    {
        $this->width = $width;
        $this->height = $height;
    }

    /**
     * @param GD $thumb
     * @param $attribute
     * @param $owner
     */
    public function process(GD $thumb, $attribute, $owner): void
    {
        $resized_image = $thumb->crop($owner->x, $owner->y, $owner->w, $owner->h);
        if (!empty($this->width) || !empty($this->height)) {
            $thumb->resize($this->width, $this->height);
        }
    }
}
