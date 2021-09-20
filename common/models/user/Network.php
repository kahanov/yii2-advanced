<?php

namespace common\models\user;

use yii\db\ActiveRecord;
use Webmozart\Assert\Assert;

/**
 * External authorization services entity
 * @property integer $user_id
 * @property string $identity
 * @property string $network
 */
class Network extends ActiveRecord
{
	/**
	 * @return string
	 */
	public static function tableName()
	{
		return '{{%user_network}}';
	}
	
	/**
	 * @param $network
	 * @param $identity
	 * @return Network
	 */
	public static function create($network, $identity): self
	{
		//Short check for void
		Assert::notEmpty($network);
		Assert::notEmpty($identity);
		
		$item = new static();
		$item->network = $network;
		$item->identity = $identity;
		
		return $item;
	}
	
	/**
	 * @param $network
	 * @param $identity
	 * @return bool
	 */
	public function isFor($network, $identity): bool
	{
		return $this->network === $network && $this->identity === $identity;
	}
}
