<?php


namespace Inc\Instagram;


use Inc\Base\Post;
use Inc\Base\SocialNetwork;

class InstagramAuth extends SocialNetwork {

	private $userId;
	private $postsCount;

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

	/**
	 * @return mixed
	 */
	public function getPostsCount() {
		return $this->postsCount;
	}

	/**
	 * @param mixed $postsCount
	 */
	public function setPostsCount( $postsCount ) {
		$this->postsCount = $postsCount;
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
			$body = json_decode(wp_remote_retrieve_body($response), true);


			if(isset($body['access_token'])) {
				$this->setAccessToken( $body['access_token'] );
				$this->setUserId($body['user_id']);
				$_SESSION['instagram_access_token'] = $this->getAccessToken();
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
		return $body;
	}

	public function getUserPosts() {
		$arg = array(
			'body'=>array(
				'access_token'=>$this->getAccessToken(),
				'fields'=>'caption,media_type,media_url,timestamp,permalink'
			)
		);
		$response = wp_remote_get('https://graph.instagram.com/'. $this->getUserId() .'/media', $arg);
		$body = json_decode(wp_remote_retrieve_body($response),true);
		return $this->filterIGPosts($body['data']);
	}
	private function filterIGPosts($payload){
		$res = array();
		foreach ($payload as $post ){
			if($post['media_type']=='IMAGE'){
				array_push($res,$post);
			}
		}
		return $res;
	}

	protected function serializeUserData( $payload ) {
		return array(
			'name' => $payload['username'],
			'social_id' => $payload['id']
		);
	}

	protected function serializeDataForDB( $postData, Post $post ) {
		$post->setAuthor(get_current_user_id());
		$post->setMetaData('social_id', $postData['id']);
		$post->setMetaData('post_url', $postData['permalink']);
		if(isset($postData['caption'])){
			$post->setContent($postData['caption']);
		}
		$post->setMetaData('post_img', $postData['media_url']);
		$post->setMetaData('post_date', $postData['timestamp']);

	}
}