<?php


namespace Inc\Base;


abstract class SocialNetwork{

	private $clientCallback;
	private $client;
	private $app_id;
	private $app_secret;
	private $accessToken;
	private $bqClient;



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
		if(class_exists('VDBigQuery')) {
			$this->bqClient = new \VDBigQuery();
		}
	}

	/**
	 * @return mixed
	 */
	public function getClient() {
		return $this->client;
	}

	/**
	 * @param mixed $client
	 */
	public function setClient( $client ) {
		$this->client = $client;
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
	/**
	 * @return \VDBigQuery
	 */
	public function getBqClient() {
		return $this->bqClient;
	}

	/**
	 * @param \VDBigQuery $bqClient
	 */
	public function setBqClient( $bqClient ) {
		$this->bqClient = $bqClient;
	}

	protected abstract function generateAccessToken();

	protected abstract function getLoginUrl();

	public abstract function getUserData();
	protected abstract function saveUserData($payload);
	protected abstract function serializeUserData($payload);


	protected abstract function getUserPosts();
	protected function savePosts($payload){
		foreach($payload as $post){
			$postData = json_decode(json_encode($post), true);
			$this->savePost($postData);

		}
	}
	protected function savePost($postData){
		$post = new Post();
		$this->serializeDataForDB($postData, $post);
		$saved = $post->savePost();

		if(class_exists('VDBigQuery') and $saved) {
			$this->bqClient->addInTable( $this->getBqClient()->getTableId(), $this->getBqClient()->getDatasetId(), $this->serializeDataForBQ( $post ) );
		}
	}

	public abstract function serializeDataForDB($postData,Post $post);
	protected abstract function serializeDataForBQ(Post $post);

	public abstract function renderShortcode();

}