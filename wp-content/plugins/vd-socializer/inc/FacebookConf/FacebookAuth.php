<?php


namespace Inc\FacebookConf;

use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;
use Inc\Base\Post;
use Inc\Base\SocialNetwork;

class FacebookAuth extends SocialNetwork {

	public $client;

	protected $helper;


	protected $permissions;

	protected $loginUrl;

	protected $userNode;

	protected $response;

	public function __construct() {
		if ( get_option( 'vd_facebook_app' ) !== "" && get_option( 'vd_facebook_secret' ) !== "" ) {

			SocialNetwork::__construct( home_url( '/wp-content/plugins/vd-socializer/inc/FacebookConf/FacebookCallback.php' ), get_option( 'vd_facebook_app' ), get_option( 'vd_facebook_secret' ) );

			add_shortcode( 'facebook', array( $this, 'renderShortcode' ) );

			$this-> client  = $this->apiInit();

			//Getting the helper
			$this->helper = $this->client -> getRedirectLoginHelper();
			$this->permissions = [ 'public_profile', 'email' , 'user_posts']; //you may change it according to your need.

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
		$this->loginUrl = $this->helper->getLoginUrl($this->getClientCallback());
		return $this->loginUrl;
	}

	public function getUserData() {
		try {
			$this->response = $this->client->get('/me?fields=email,first_name,last_name,name,picture');
			$this->userNode = $this->response->getGraphNode();
		} catch (FacebookResponseException $e) {
			die('Graph phase 1: error in processing your request while fetching token'); // you can add here your own error handling
		} catch (FacebookSDKException $e) {
			var_dump('Here' . $e->getMessage());
		}

		return $this->userNode;
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
		$response = $this->client->get('/me/feed');
		$graphNode = $response->getGraphEdge();
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
			$this->client->setDefaultAccessToken($_SESSION['facebook_access_token']);
			$userData = $this->getUserData();
			$posts = $this->getUserPosts();

			$this->saveUserData($userData->asArray());
			//$this->savePosts($posts);

			include( PLUGIN_PATH . '/inc/FacebookConf/facebookAccount.php' );
		}else {
			return '<p><a href="' . $this->getLoginUrl() . '">Sign in with Facebook</a></p>';

		}
	}
}