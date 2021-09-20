<?php

namespace common\models\user;

use common\behaviors\AvatarUploadBehavior;
use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;

/**
 * @property integer $id
 * @property integer $user_id
 * @property string $name
 * @property integer $experience
 * @property string $contact_email
 * @property string $description
 * @property string $phone
 * @property string $website
 * @property string $skype
 * @property array $operating_time
 * @property string $logotype
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $country_id
 * @property integer $region_id
 * @property integer $district_id
 * @property integer $city_id
 * @property integer $street_id
 * @property integer $house_number
 * @property string $address
 * @property string $coordinates
 *
 * @property User $user
 */
class Company extends ActiveRecord
{

    /**
     * @return string
     */
    public static function tableName()
    {
        return 'company';
    }

    /**
     * @return array
     */
    public function behaviors(): array
    {
        return [
            TimestampBehavior::class,
            [
                'class' => AvatarUploadBehavior::class,
                'attribute' => 'logotype',
                'createThumbsOnSave' => false,
                'createThumbsOnRequest' => false,
                'filePath' => '@staticRoot/origin/user/[[attribute_user_id]]/avatar/logotype_[[attribute_user_id]].[[extension]]',
                'fileUrl' => '@static/origin/origin/user/[[attribute_user_id]]/avatar/logotype_[[attribute_user_id]].[[extension]]',
                'thumbPath' => '@staticRoot/cache/user/[[attribute_user_id]]/avatar/[[profile]]_[[attribute_user_id]].[[extension]]',
                'thumbUrl' => '@static/cache/user/[[attribute_user_id]]/avatar/[[profile]]_[[attribute_user_id]].[[extension]]',
            ],
        ];
    }

    public function afterFind(): void
    {
        if (!empty($this->getAttribute('operating_time'))) {
            $this->operating_time = @unserialize($this->getAttribute('operating_time'));
        }
        parent::afterFind();
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert): bool
    {
        if (!empty($this->operating_time)) {
            if (is_array($this->operating_time)) {
                $this->setAttribute('operating_time', serialize($this->operating_time));
            } else {
                $this->setAttribute('operating_time', NULL);
            }
        }
        return parent::beforeSave($insert);
    }

    /**
     * @return ActiveQuery
     */
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
