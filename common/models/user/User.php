<?php

namespace common\models\user;

use common\behaviors\AvatarUploadBehavior;
use Yii;
use yii\base\NotSupportedException;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\behaviors\TimestampBehavior;
use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use yii\web\UploadedFile;
use common\services\ImageCrop;
use yii\helpers\ArrayHelper;

/**
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $email_confirm_token
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 * @property string $first_name
 * @property string $last_name
 * @property string $avatar
 * @property integer $date_birth
 * @property string $facebook
 * @property string $vk
 * @property string $ok
 *
 * @property Network $networks
 * @property Company $company
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_WAIT = 0;
    const STATUS_ACTIVE = 10;

    public $logotype;
    public $x;
    public $y;
    public $x2;
    public $y2;
    public $w;
    public $h;

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return 'user';
    }

    /**
     * @return array
     */
    public function behaviors(): array
    {
        return [
            TimestampBehavior::class,
            [
                'class' => SaveRelationsBehavior::class,
                'relations' => ['networks', 'companyAssignments'],
            ],
            [
                'class' => AvatarUploadBehavior::class,
                'attribute' => 'avatar',
                'createThumbsOnRequest' => false,
                'filePath' => '@staticRoot/origin/user/[[id]]/avatar/avatar_[[id]].[[extension]]',
                'fileUrl' => '@static/origin/origin/user/[[id]]/avatar/avatar_[[id]].[[extension]]',
                'thumbPath' => '@staticRoot/cache/user/[[id]]/avatar/[[profile]]_[[id]].[[extension]]',
                'thumbUrl' => '@static/cache/user/[[id]]/avatar/[[profile]]_[[id]].[[extension]]',
                'thumbs' => [
                    'avatar' => ['processor' => [new ImageCrop(100, 100), 'process']],
                ],
            ],
            [
                'class' => AvatarUploadBehavior::class,
                'attribute' => 'logotype',
                'createThumbsOnRequest' => false,
                'filePath' => '@staticRoot/origin/user/[[id]]/avatar/logotype_[[id]].[[extension]]',
                'fileUrl' => '@static/origin/origin/user/[[id]]/avatar/logotype_[[id]].[[extension]]',
                'thumbPath' => '@staticRoot/cache/user/[[id]]/avatar/[[profile]]_[[id]].[[extension]]',
                'thumbUrl' => '@static/cache/user/[[id]]/avatar/[[profile]]_[[id]].[[extension]]',
                'thumbs' => [
                    'logotype' => ['processor' => [new ImageCrop(100, 100), 'process']],
                ],
            ],
        ];
    }

    /**
     * @return int|mixed|string
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @return string
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @param int|string $id
     * @return User|null|IdentityInterface
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @param mixed $token
     * @param null $type
     * @return null|void|IdentityInterface
     * @throws NotSupportedException
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * @return array
     */
    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    /**
     * Registration request
     * @param string $username
     * @param string $email
     * @param string $password
     * @return User
     * @throws \yii\base\Exception
     */
    public static function requestSignup(string $username, string $email, string $password): self
    {
        $user = new User();
        $user->username = $username;
        $user->email = $email;
        $user->setPassword($password);
        $user->created_at = time();
        $user->status = self::STATUS_WAIT;
        $user->email_confirm_token = Yii::$app->security->generateRandomString();
        $user->generateAuthKey();

        return $user;
    }

    /**
     * Registration confirmation
     */
    public function confirmSignup(): void
    {
        if (!$this->isWait()) {
            throw new \DomainException(Yii::t('frontend/user', 'Пользователь уже активен'));
        }

        $this->status = self::STATUS_ACTIVE;
        $this->email_confirm_token = null;
    }

    /**
     * Registration with external services
     * @param $network
     * @param $identity
     * @param $attributes
     * @return User
     * @throws \Exception
     */
    public static function signupByNetwork($network, $identity, $attributes): self
    {
        $email = ArrayHelper::getValue($attributes, 'email');
        $email = (empty($email)) ? ArrayHelper::getValue($attributes, 'default_email') : $email;
        $firstName = ArrayHelper::getValue($attributes, 'first_name');
        $lastName = ArrayHelper::getValue($attributes, 'last_name');
        $nickName = ArrayHelper::getValue($attributes, 'nickname');
        $nickName = (empty($nickName)) ? ArrayHelper::getValue($attributes, 'display_name') : $nickName;

        $user = new User();
        $user->created_at = time();
        $user->status = self::STATUS_ACTIVE;
        $user->generateAuthKey();
        $user->email = $email;
        $user->first_name = $firstName;
        $user->last_name = $lastName;
        $user->username = ($nickName) ? $nickName : $email;
        $user->networks = [
            Network::create($network, $identity)
        ];
        return $user;
    }

    /**
     * @param $network
     * @param $identity
     */
    public function addNetwork($network, $identity): void
    {
        $this->networks = [
            Network::create($network, $identity)
        ];
    }

    /**
     * Password reset request
     * @throws \yii\base\Exception
     */
    public function requestPasswordReset(): void
    {
        if (!empty($this->password_reset_token) && self::isPasswordResetTokenValid($this->password_reset_token)) {
            throw new \DomainException(Yii::t('frontend/user', 'Сброс пароля уже запрошен, пожалуйста, проверьте свой email'));
        }

        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * @param $password
     * @throws \yii\base\Exception
     */
    public function resetPassword($password): void
    {
        if (empty($this->password_reset_token)) {
            throw new \DomainException(Yii::t('frontend/user', 'Сброс пароля не требуется'));
        }

        $this->setPassword($password);
        $this->password_reset_token = null;
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int)substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * Create user
     * @param string $username
     * @param string $email
     * @param string $password
     * @return User
     * @throws \yii\base\Exception
     */
    public static function create(string $username, string $email, string $password): self
    {
        $user = new User();
        $user->username = $username;
        $user->email = $email;
        $user->setPassword(!empty($password) ? $password : Yii::$app->security->generateRandomString());
        $user->created_at = time();
        $user->status = self::STATUS_ACTIVE;
        $user->auth_key = Yii::$app->security->generateRandomString();
        return $user;
    }

    /**
     * @return ActiveQuery
     */
    public function getNetworks(): ActiveQuery
    {
        return $this->hasMany(Network::class, ['user_id' => 'id']);
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     * @param $password
     * @throws \yii\base\Exception
     */
    private function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     * @throws \yii\base\Exception
     */
    private function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Registration is confirmed
     * @return bool
     */
    public function isWait(): bool
    {
        return $this->status === self::STATUS_WAIT;
    }

    /**
     * Whether the user is activated
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    /**
     * Network is already attached
     * @param $network
     * @param $identity
     */
    public function attachNetwork($network, $identity): void
    {
        $networks = $this->networks;

        foreach ($networks as $current) {
            if ($current->isFor($network, $identity)) {
                throw new \DomainException(Yii::t('frontend/user', 'Сеть уже подключена'));
            }
        }

        $networks[] = Network::create($network, $identity);
        $this->networks = $networks;
    }

    public function setAvatar(UploadedFile $avatar): void
    {
        $this->avatar = $avatar;
    }

    public function setLogotype(UploadedFile $logotype): void
    {
        $this->logotype = $logotype;
    }

    /**
     * @return array
     */
    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('common', 'Идентификатор'),
            'username' => Yii::t('backend/user', 'Логин'),
            'email' => Yii::t('backend/user', 'Email'),
            'status' => Yii::t('backend/user', 'Статус'),
            'created_at' => Yii::t('backend/user', 'Дата создания'),
            'updated_at' => Yii::t('backend/user', 'Дата обновления'),
            'role' => Yii::t('backend/user', 'Роль'),
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getCompany(): ActiveQuery
    {
        return $this->hasOne(Company::class, ['user_id' => 'id']);
    }
}

