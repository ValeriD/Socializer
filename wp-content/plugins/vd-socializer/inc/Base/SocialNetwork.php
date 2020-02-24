<?php


namespace Inc\Base;


abstract class SocialNetwork{

	private $clientCallback;
	private $app_id;
	private $app_secret;


	private $accessToken;

	/**
	 * SocialNetwork constructor.
	 *
	 * @param string $clientCallback
	 * @param $app_id
	 * @param $app_secret
	 */
	public function __construct( $clientCallback, $app_id, $app_secret ) {
		$this->clientCallback = $clientCallback;
		$this->app_id         = $app_id;
		$this->app_secret     = $app_secret;
	}
	/**
	 * @return string
	 */
	public function getClientCallback() {
		return $this->clientCallback;
	}

	/**
	 * @param string $clientCallback
	 */
	public function setClientCallback( $clientCallback ) {
		$this->clientCallback = $clientCallback;
	}

	/**
	 * @return mixed
	 */
	public function getAppId() {
		return $this->app_id;
	}

	/**
	 * @param mixed $app_id
	 */
	public function setAppId( $app_id ) {
		$this->app_id = $app_id;
	}

	/**
	 * @return mixed
	 */
	public function getAppSecret() {
		return $this->app_secret;
	}

	/**
	 * @param mixed $app_secret
	 */
	public function setAppSecret( $app_secret ) {
		$this->app_secret = $app_secret;
	}

	/**
	 * @return mixed
	 */
	public function getAccessToken() {
		return $this->accessToken;
	}

	/**
	 * @param mixed $accessToken
	 */
	public function setAccessToken( $accessToken ) {
		$this->accessToken = $accessToken;
	}

	protected abstract function generateAccessToken();

	protected abstract function storeToken();

	protected abstract function getLoginUrl();

	public abstract function getUserData();

	protected abstract function saveUserData($payload);

	public abstract function renderShortcode();

}