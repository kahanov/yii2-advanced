<?php

namespace frontend\components;

use yii\authclient\InvalidResponseException;
use yii\authclient\OAuth2;

class MailRuAuthClient extends OAuth2
{
	public $authUrl = 'https://connect.mail.ru/oauth/authorize';
	public $tokenUrl = 'https://connect.mail.ru/oauth/token';
	public $apiBaseUrl = 'http://www.appsmail.ru/platform/api?method=';
	
	/**
	 * @return array
	 * @throws InvalidResponseException
	 * @throws \yii\httpclient\Exception
	 */
	protected function initUserAttributes()
	{
		$request = $this->createApiRequest()->setMethod('GET')->setUrl('users.getInfo');
		$response = $request->send();
		$response->setFormat('json');
		if ($response->isOk && $response->data && $response->data['0']) {
			return $response->data['0'];
		}
		throw new InvalidResponseException($response);
	}
	
	/**
	 * @param $request
	 * @param $accessToken
	 */
	public function applyAccessTokenToRequest($request, $accessToken)
	{
		parent::applyAccessTokenToRequest($request, $accessToken);
		$data = $request->getData();
		$data['method'] = str_replace('/', '', $request->getUrl());
		$data['uids'] = $accessToken->getParam('x_mailru_vid');
		$data['app_id'] = $this->clientId;
		$data['secure'] = 1;
		$data['sig'] = $this->sig($data, $this->clientSecret);
		$request->setUrl('');
		$request->setData($data);
	}
	
	/**
	 * @param array $request_params
	 * @param $secret_key
	 * @return string
	 */
	public function sig(array $request_params, $secret_key)
	{
		ksort($request_params);
		$params = '';
		foreach ($request_params as $key => $value) {
			$params .= "$key=$value";
		}
		return md5($params . $secret_key);
	}
	
	/**
	 * @inheritdoc
	 */
	protected function defaultName()
	{
		return 'mailru';
	}
	
	/**
	 * @inheritdoc
	 */
	protected function defaultTitle()
	{
		return 'MailRu';
	}
	
	/**
	 * @inheritdoc
	 */
	protected function defaultNormalizeUserAttributeMap()
	{
		return [
			'id' => 'uid'
		];
	}
}
