<?php


namespace Inc\Instagram;


use Inc\Base\Post;
use Inc\Base\SocialNetwork;

class InstagramAuth extends SocialNetwork {

	private $userId;

	public function __construct() {
		SocialNetwork::__construct( 'Instagram' );
	}

	/**
	 * @return mixed
	 */
	public function getUserId() {
		return $this->userId;
	}

	/**
	 * @param mixed $userId
	 */
	public function setUserId( $userId ) {
		$this->userId = $userId;
	}


	protected function initialize() {
	}

	public function generateAccessToken() {
			$arg = array(
				'body'=>array(
					'client_id' => $this->getAppId(),
					'client_secret' => $this->getAppSecret(),
					'grant_type' => 'authorization_code',
					'code' => $_GET['code'],
					'redirect_uri' => $this->getClientCallback()
				)
			);

			$response = wp_remote_post('https://api.instagram.com/oauth/access_token', $arg);
			$body = wp_remote_retrieve_body($response);
			$body_arr = json_decode($body, true);

			if(isset($body_arr['access_token'])) {
				$this->setAccessToken( $body_arr['access_token'] );
				$this->setUserId($body_arr['user_id']);
			}

	}

	protected function getLoginUrl() {
		$callback = $this->getClientCallback();
		str_replace('/', '%2F', $callback);
		str_replace(':', '%3A', $callback);
		return'https://api.instagram.com/oauth/authorize?client_id='. $this->getAppId() .'&redirect_uri='. $callback .'&response_type=code&scope=user_profile,user_media';
	}

	public function getUserData() {
		$arg = array(
			'body'=>array(
				'access_token' => $this->getAccessToken(),
				'fields' => 'username,media_count'
			)
		);
		$response = wp_remote_get('https://graph.instagram.com/'.$this->getUserId(), $arg);
		$body = json_decode(wp_remote_retrieve_body($response), true);
		var_dump($body['username']);
		$this->serializeUserData($body);
		//$this->saveUserData();
	}

	public function getUserPosts() {
		// TODO: Implement getUserPosts() method.
	}

	protected function serializeUserData( $payload ) {
		return array(
			'name' => $payload['username'],
			'media_count' => $payload['media_count'],
			'id' => $payload['id']
		);
	}

	protected function serializeDataForDB( $postData, Post $post ) {
		// TODO: Implement serializeDataForDB() method.
	}
}