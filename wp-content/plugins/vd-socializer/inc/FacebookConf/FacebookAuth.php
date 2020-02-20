<?php


namespace Inc\FacebookConf;

use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;
class FacebookAuth {

	/**
	 * @var Facebook
	 */
	protected $client;

	/**
	 * @var \Facebook\Helpers\FacebookRedirectLoginHelper
	 */
	protected $helper;

	/**
	 * @var array
	 */
	protected $permissions;
	/**
	 * @var
	 */
	protected $loginUrl;
	/**
	 * @var
	 */
	protected $accesstoken;
	/**
	 * @var
	 */
	protected $userNode;
	/**
	 * @var
	 */
	protected $response;
	protected $app_id ;
	protected $app_secret;
	protected $callback = 'https://socializer.com/wp-content/plugins/vd-socializer/inc/FacebookConf/FacebookCallback.php';

	public function __construct() {

	}

	public function renderShortcode(){

		$html = '<p><a href="' . $this->getFacebookAuthUrl() . '">Sign in with Facebook</a></p>';
		return $html;
	}
	/**
	 * FacebookAuth register.
	 * @throws FacebookSDKException
	 */
	public function register()
	{
		if(get_option('vd_facebook_app')!=="" && get_option('vd_facebook_secret')!=="") {


			$this->app_id     = get_option( 'vd_facebook_app' );
			$this->app_secret = get_option( 'vd_facebook_secret' );


			add_shortcode( 'facebook', array( $this, 'renderShortcode' ) );


			//Creating a Facebook app
			$this->apiInit();
			//Getting the helper
			$this->helper = $this->client->getRedirectLoginHelper();
			//Used for CSRF
			if ( isset( $_GET['state'] ) ) {
				$this->helper->getPersistentDataHandler()->set( 'state', $_GET['state'] );
			}
			$this->permissions = [ 'public_profile', 'email' ]; //you may change it according to your need.
		}
	}

	public function apiInit(){
		try {
			$this->client = new Facebook( [
				'app_id'                => $this->app_id,
				'app_secret'            => $this->app_secret,
				'default_graph_version' => "v2.8",
			] );
		} catch ( FacebookSDKException $e ) {
			var_dump('Facebook App Init: ' . $e->getMessage());
		}
	}
	/**
	 * Getting the access token
	 * @return \Facebook\Authentication\AccessToken|null
	 * @throws FacebookSDKException
	 */
	public function getFacebookAccessToken()
	{
		try {
			$accesstoken = $this->helper->getAccessToken();

		} catch (FacebookResponseException $e) {
			var_dump('phase 1: error in processing your request while fetching token'); // you can add here your own error handling
		} catch (FacebookSDKException $e) {
			var_dump('phase 2: error in processing your request with fetching token');
		}
		$this->setFacebookAccessToken($accesstoken);
		return $accesstoken;
	}

	/**
	 * Setting the access token in the Session
	 * @param $accesstoken
	 */
	public function setFacebookAccessToken($accesstoken)
	{
		if (isset($accesstoken)) {
			//Means the user is logged in from facebook
			$_SESSION['facebook_access_token'] = (string) $accesstoken;

		}
	}

	/**
	 * Getting the user's public data
	 * @return \Facebook\GraphNodes\GraphNode
	 */
	public function getGraph()

	{
		try {
			$this->client->setDefaultAccessToken($this->getFacebookAccessToken());
			$this->response = $this->client->get('/me?fields=email,first_name,last_name,verified,picture,gender'); //you may change it according to you need.
			$this->userNode = $this->response->getGraphNode();
		} catch (FacebookResponseException $e) {
			die('Graph phase 1: error in processing your request while fetching token'); // you can add here your own error handling
		} catch (FacebookSDKException $e) {
			die('Graph phase 2: error in processing your request with fetching token');
		}

		return $this->userNode;
	}

	/**
	 * Getting the login url
	 * @return string
	 */
	public function getFacebookAuthUrl()
	{
		$this->loginUrl = $this->helper->getLoginUrl($this->callback);
		return $this->loginUrl;
	}

	/**
	 * Unsetting the access token => the user logged out
	 * @param string $value
	 */
	public function facebookLogOut($value='')
	{
		unset($_SESSION['facebook_access_token']);
		return;
	}
}