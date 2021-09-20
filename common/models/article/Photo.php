<?php

namespace common\models\article;

use common\services\WaterMarker;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;
use yiidreamteam\upload\ImageUploadBehavior;

/**
 * @property integer $id
 * @property string $file
 *
 * @mixin ImageUploadBehavior
 */
class Photo extends ActiveRecord
{
    /**
     * @param UploadedFile $file
     * @return Photo
     */
    public static function create(UploadedFile $file): self
    {
        $photo = new static();
        $photo->file = $file;
        return $photo;
    }

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return 'article_photo';
    }

    /**
     * @return array
     */
    public function behaviors(): array
    {
        return [
            [
                'class' => ImageUploadBehavior::class,
                'attribute' => 'file',
                'createThumbsOnRequest' => true,
                'filePath' => '@staticRoot/origin/article/[[attribute_article_id]]/[[id]].[[extension]]',
                'fileUrl' => '@static/origin/article/[[attribute_article_id]]/[[id]].[[extension]]',
                'thumbPath' => '@staticRoot/cache/article/[[attribute_article_id]]/[[profile]]_[[id]].[[extension]]',
                'thumbUrl' => '@static/cache/article/[[attribute_article_id]]/[[profile]]_[[id]].[[extension]]',
                'thumbs' => [
                    'admin' => ['width' => 100, 'height' => 70],
                    'thumb' => ['width' => 640, 'height' => 480],
                    'article_list' => ['width' => 186, 'height' => 139],
                    //'origin' => ['processor' => [new WaterMarker(1024, 768, '@frontend/web/image/logo.png'), 'process']],
                    'origin' => ['width' => 1024, 'height' => 768],
                ],
            ],
        ];
    }
}
