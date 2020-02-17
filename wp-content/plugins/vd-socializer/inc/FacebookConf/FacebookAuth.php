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


	public function __construct() {


		try {
			$this->register();
		} catch ( FacebookSDKException $e ) {
			var_dump('FacebookSDK returned: '. $e->getMessage());
		}
		add_shortcode('facebook', array($this, 'renderShortcode'));

	}

	public function renderShortcode(){
		$html = '<a href="' . $this->getFacebookAuthUrl() . '">Sign in with Facebook</a>';
		return $html;
	}
	/**
	 * FacebookAuth register.
	 * @throws FacebookSDKException
	 */
	public function register()
	{

		//Creating a Facebook app
		$this->apiInit();
		//Getting the helper
		$this->helper = $this->client->getRedirectLoginHelper();
		//Used for CSRF
		if (isset($_GET['state'])) {
			$this->helper->getPersistentDataHandler()->set('state', $_GET['state']);
		}
		$this->permissions = [ 'public_profile' , 'email' ]; //you may change it according to your need.

	}

	public function apiInit(){
		$this->client = new Facebook([
			'app_id' => "2187669774872877",
			'app_secret' => "ce5ec9e50a0d4a39629208c6facdc0b7",
			'default_graph_version' => "v2.8",
		]);
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

		} catch (Facebook\Exceptions\FacebookResponseException $e) {
			var_dump('phase 1: error in processing your request while fetching token'); // you can add here your own error handling
		} catch (Facebook\Exceptions\FacebookSDKException $e) {
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
		return $this->loginUrl = $this->helper->getLoginUrl('http://exercise.org/callback.php', $this->permissions);
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