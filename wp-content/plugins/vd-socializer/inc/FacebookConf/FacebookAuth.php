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

	public function renderShortcode(){
		if(isset($_SESSION['facebook_access_token'])) {
			$this->client->setDefaultAccessToken($_SESSION['facebook_access_token']);
			$userData = $this->getUserData();
			$posts = $this->getUserPosts();
			$html = '<p><a href="'.PLUGIN_URL.'/inc/FacebookConf/facebookLogout.php">Logout</a></p>';
		}else {
			$html = '<p><a href="' . $this->getLoginUrl() . '">Sign in with Facebook</a></p>';

		}
		return $html;
	}


	public function apiInit(){
			return new Facebook( [
				'app_id'                => $this->getAppId(),
				'app_secret'            => $this->getAppSecret(),
				'default_graph_version' => "v2.8",
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
			$this->response = $this->client->get('/me?fields=email,first_name,last_name,verified,picture,gender');
			$this->userNode = $this->response->getGraphNode();
		} catch (FacebookResponseException $e) {
			die('Graph phase 1: error in processing your request while fetching token'); // you can add here your own error handling
		} catch (FacebookSDKException $e) {
			var_dump('Here' . $e->getMessage());
		}

		return $this->userNode;
	}

	protected function saveUserData( $payload ) {

	}

	protected function serializeUserData( $payload ) {
		// TODO: Implement serializeUserData() method.
	}

	protected function getUserPosts() {
		$response = $this->client->get('/me/feed');
		$graphNode = $response->getGraphEdge();
		var_dump($graphNode);
		return $graphNode;
	}

	public function serializeDataForDB( $postData, Post $post ) {
		// TODO: Implement serializeDataForDB() method.
	}

	protected function serializeDataForBQ( Post $post ) {
		// TODO: Implement serializeDataForBQ() method.
	}
}