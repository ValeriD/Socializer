<?php


namespace Inc\FacebookConf;

use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;
use Facebook\GraphNodes\GraphNode;
use Inc\Base\Post;
use Inc\Base\SocialNetwork;

class FacebookAuth extends SocialNetwork {


	private $helper;

	private $permissions;


	public function __construct() {
		if ( get_option( 'vd_facebook_app' ) !== "" && get_option( 'vd_facebook_secret' ) !== "" ) {

			SocialNetwork::__construct( home_url( '/wp-content/plugins/vd-socializer/inc/FacebookConf/FacebookCallback.php' ), get_option( 'vd_facebook_app' ), get_option( 'vd_facebook_secret' ) );

			add_shortcode( 'facebook', array( $this, 'renderShortcode' ) );

			$this->setClient($this->apiInit());

			//Getting the helper
			$this->helper = $this->getClient() -> getRedirectLoginHelper();
			$this->permissions = ['public_profile', 'email', 'user_location', 'user_hometown','user_birthday','user_photos', 'user_posts']; //you may change it according to your need.
			//Used for CSRF
			if ( isset( $_GET['state'] ) ) {
			$this->helper->getPersistentDataHandler()->set( 'state', $_GET['state'] );
			}
		}
	}




	public function apiInit(){
			return new Facebook( [
				'app_id'                => $this->getAppId(),
				'app_secret'            => $this->getAppSecret(),
				'default_graph_version' => "v5.0",
			] );
	}





	public function generateAccessToken() {
		try {
			$accesstoken = $this->helper->getAccessToken();

		} catch (FacebookResponseException $e) {
			var_dump($e->getMessage());
		} catch (FacebookSDKException $e) {
			var_dump($e->getMessage());
		}
		$_SESSION['facebook_access_token'] = $accesstoken;
		return $accesstoken;
	}


	protected function getLoginUrl() {
		return $this->helper->getLoginUrl($this->getClientCallback(), $this->permissions);

	}

	public function getUserData() {
		try {
			$response = $this->getClient()->get('/me?fields=email,first_name,last_name,name,picture,hometown,birthday');
			$userNode = $response->getGraphUser();
		} catch (FacebookResponseException $e) {
			var_dump('Graph phase 1: error in processing your request while fetching token'); // you can add here your own error handling
		} catch (FacebookSDKException $e) {
			var_dump('Here ' . $e->getMessage());
		}

		return $userNode;
	}

	protected function saveUserData( $payload ) {
		update_user_meta(get_current_user_id(), 'facebook_account' ,$this->serializeUserData($payload));
	}

	protected function serializeUserData( $payload ) {
		$res =  array(
			'name' => $payload->getName(),
			'social_id' => $payload->getId(),
			'email'=>$payload->getEmail(),
			'user_img'=>$payload->getPicture()['url'],
			'hometown' => $payload->getHometown()['name'],
			'birthday' => $payload->getBirthday()->format('d/m/Y')
		);
		return $res;
	}

	protected function getUserPosts() {
		try {
			// create request on me/posts?fields=id,message,caption,shares,likes,created_time,picture,link,attachments and then check the fields
			// you can use, because attachments is better than picture field in the request

			$response = $this->getClient()->get( '/me/posts?fields=id,message,caption,shares,likes.summary(true),created_time,picture,link' );
		} catch ( FacebookSDKException $e ) {
			var_dump($e->getMessage());
		}
		try {
			$graphNode = $response->getGraphEdge();
		} catch ( FacebookSDKException $e ) {
			var_dump($e->getMessage());
		}


		$posts = $this->filterPosts($graphNode);
		return $posts;
	}

	private function filterPosts($graphNode){
		$result = array();

		foreach ($graphNode as $post){
			//var_dump($post);
			if($post->getField('link') and ($post->getField('message') or $post->getField('caption'))){
				$result[] = $post;
			}
		}
		return $result;
	}


	public function serializeDataForDB( $postData, Post $post ) {
		$post->setAuthor(get_current_user_id());
		$post->setMetaData('social_id', $postData->getField('id'));
		$post->setMetaData('post_link', $postData->getField('link'));

		if($postData->getField('message')){
			$post->setTitle($postData->getField('message'));
			$post->setContent($postData->getField('message'));
		}
		if($postData->getField('caption')) {
			$post->setTitle($postData->getField('message'));
			$post->setContent( $postData->getField('caption') );
		}
		if($postData->getField('picture')){
			$post->setMetaData('post_img', $postData->getField('picture'));
		}

		if($postData->getField( 'created_time')){
			$post->setMetaData('post_date',$postData->getField( 'created_time'));
		}

		if($postData->getField('likes')){
			$post->setMetaData('post_likes', $postData->getField('likes')->getTotalCount());
		}

		if($postData->getField('shares')){
			$post->setMetaData('post_shares', $postData->getField('shares')['count']);
		}
		var_dump($post);

	}

	protected function serializeDataForBQ( Post $post ) {
		// TODO: Implement serializeDataForBQ() method.
	}

	public function renderShortcode(){
		if(isset($_SESSION['facebook_access_token'])) {
			$this->getClient()->setDefaultAccessToken($_SESSION['facebook_access_token']);

			$userData = $this->getUserData();
			$this->saveUserData($userData);


			$posts = $this->getUserPosts();
			$this->savePosts($posts);
			include( PLUGIN_PATH . '/inc/FacebookConf/facebookAccount.php' );
		}else {
			return '<p><a href="' . $this->getLoginUrl() . '">Sign in with Facebook</a></p>';

		}
	}


}