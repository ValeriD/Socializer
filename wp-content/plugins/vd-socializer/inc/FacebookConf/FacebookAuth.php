<?php


namespace Inc\FacebookConf;

use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;
use Inc\Base\Post;
use Inc\Base\SocialNetwork;

class FacebookAuth extends SocialNetwork {


	protected $helper;

	protected $permissions;


	public function __construct() {
		if ( get_option( 'vd_facebook_app' ) !== "" && get_option( 'vd_facebook_secret' ) !== "" ) {

			SocialNetwork::__construct( home_url( '/wp-content/plugins/vd-socializer/inc/FacebookConf/FacebookCallback.php' ), get_option( 'vd_facebook_app' ), get_option( 'vd_facebook_secret' ) );

			add_shortcode( 'facebook', array( $this, 'renderShortcode' ) );

			$this->setClient($this->apiInit());

			//Getting the helper
			$this->helper = $this->getClient() -> getRedirectLoginHelper();
			$this->permissions = ['public_profile', 'email', 'user_location', 'user_hometown','user_photos']; //you may change it according to your need.
			var_dump($this->permissions);
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
				'default_graph_version' => "v6.0",
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
			$response = $this->getClient()->get('/me?fields=email,first_name,last_name,name,picture,hometown');
			$userNode = $response->getGraphNode();
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
		return array(
			'name' => $payload['name'],
			'social_id' => $payload['id'],
			'email'=>$payload['email'],
			'user_img'=>$payload['picture']['url']
		);
	}

	protected function getUserPosts() {
		try {
			$response = $this->getClient()->get( '/me/photos' );
		} catch ( FacebookSDKException $e ) {
			var_dump($e->getMessage());
		}
		try {
			$graphNode = $response->getGraphEdge();
		} catch ( FacebookSDKException $e ) {
			var_dump($e->getMessage());
		}

		//var_dump($graphNode);
		return $graphNode;
	}

	public function serializeDataForDB( $postData, Post $post ) {

	}

	protected function serializeDataForBQ( Post $post ) {
		// TODO: Implement serializeDataForBQ() method.
	}

	public function renderShortcode(){
		if(isset($_SESSION['facebook_access_token'])) {
			$this->getClient()->setDefaultAccessToken($_SESSION['facebook_access_token']);
			$userData = $this->getUserData();
			var_dump($userData);

			//var_dump($userData);
			$this->saveUserData($userData->asArray());
			//$this->savePosts($posts);
			$posts = $this->getUserPosts();
			var_dump($posts);
			include( PLUGIN_PATH . '/inc/FacebookConf/facebookAccount.php' );
		}else {
			return '<p><a href="' . $this->getLoginUrl() . '">Sign in with Facebook</a></p>';

		}
	}
}