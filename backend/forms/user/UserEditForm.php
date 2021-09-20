<?php

namespace backend\forms\user;

use Yii;
use yii\base\Model;
use common\models\user\User;

class UserEditForm extends Model
{
	public $username;
	public $email;
	public $role;
	public $is_api;
	public $api_key;
	public $_user;
	
	/**
	 * UserEditForm constructor.
	 * @param User $user
	 * @param array $config
	 */
	public function __construct(User $user, $config = [])
	{
		$this->username = $user->username;
		$this->email = $user->email;
		$roles = Yii::$app->authManager->getRolesByUser($user->id);
		$this->role = $roles ? reset($roles)->name : null;
		$this->_user = $user;
		
		parent::__construct($config);
	}
	
	/**
	 * @return array
	 */
	public function rules(): array
	{
		return [
			[['username', 'email', 'role', 'is_api'], 'required'],
			['email', 'email'],
			['email', 'string', 'max' => 255],
            [['is_api'], 'boolean'],
            [['api_key'], 'string'],
			[['username', 'email'], 'unique', 'targetClass' => User::class, 'filter' => ['<>', 'id', $this->_user->id]],
		];
	}
}
