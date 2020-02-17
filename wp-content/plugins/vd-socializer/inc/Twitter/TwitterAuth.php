<?php


namespace Inc\Twitter;


use Abraham\TwitterOAuth\TwitterOAuth;
use Abraham\TwitterOAuth\TwitterOAuthException;

/**
 * Class TwitterAuth
 * @package Inc\Twitter
 */

class TwitterAuth {
	/**
	 * @var TwitterOAuth
	 */
	protected $client;
	/**
	 * @var string
	 */
	protected $clientCallback = "http://exercise.org"; //TODO
	/**
	 * @var string
	 */
	protected $app_id = "odGJtUtFaP6urmMDq6SzlsH6Q";
	/**
	 * @var string
	 */
	protected $app_secret = "zNliHCE2b3tFjgWGP2jAbc6SW4KgUjmE2W6xHvuXR7MnusBGyl";

	/**
	 * TwitterAuth constructor.
	 *
	 * @param TwitterOAuth $client
	 */
	public function __construct()
	{
		$this->app_id = get_option('vd_twitter_app');
		$this->app_secret = get_option('vd_twitter_secret');

		$this->client = new TwitterOAuth($this->app_id,$this->app_secret);
		add_shortcode('twitter', array($this, 'renderShortcode'));
	}


	/**
	 * Function for generating the acceess token
	 * @return bool|array
	 * @throws \Abraham\TwitterOAuth\TwitterOAuthException
	 */
	protected function generateAccessToken()
	{
		if (!isset($_SESSION['twitter_auth'])) {
			return $this->client->oauth('oauth/request_token', ['oauth_callback' => $this->clientCallback]);
		}

		return false;
	}

	/**
	 * Saving the access token in the session
	 * @return bool
	 * @throws \Abraham\TwitterOAuth\TwitterOAuthException
	 */
	protected function storeToken()
	{

		if (!isset($_SESSION['twitter_auth'])) {
			//storing the token into the session.

			$accessToken = $this->generateAccessToken();

			$_SESSION['twitter_auth'] = "started";
			$_SESSION['oauth_token'] = $accessToken['oauth_token'];
			$_SESSION['oauth_token_secret'] = $accessToken['oauth_token'];

			return $accessToken;

		}

		return false;

	}

	/**
	 * @return mixed
	 */
	public function getUrl()
	{
		try {
			$token = $this->storeToken();
		} catch ( TwitterOAuthException $e ) {
			var_dump('TwitterOAuthException: ' . $e->getMessage());
		}

		return $this->client->url('oauth/authorize', ['oauth_token' => $_SESSION['oauth_token'] ]);
	}

	/**
	 * @return array|bool
	 */
	protected function verifyToken()
	{
		$requestToken = [];
		$requestToken['oauth_token'] = $_SESSION['oauth_token'];
		$requestToken['oauth_token_secret'] = $_SESSION['oauth_token_secret'];
		unset($_SESSION['twitter_auth']);

		if (isset($_REQUEST['oauth_token']) && $requestToken['oauth_token'] !== $_REQUEST['oauth_token']) {
			return false; // if token mismatch
		}
		return $requestToken;
	}

	/**
	 * @return array|bool|object
	 *
	 */
	public function getPayload()
	{
		$requestToken = $this->verifyToken();
		if (!$this->verifyToken()) {
			return false;
		}

		$connection = new TwitterOAuth($this->app_id,$this->app_secret, $requestToken['oauth_token'], $requestToken['oauth_token_secret']);


		try {
			$accessToken = $connection->oauth( 'oauth/access_token', [ 'oauth_verifier' => $_REQUEST['oauth_verifier'] ] );
		} catch ( TwitterOAuthException $e ) {
			var_dump('TwitterOAuthException: ' . $e->getMessage());
		}

		$connection = new TwitterOAuth($this->app_id, $this->app_secret, $accessToken['oauth_token'], $accessToken['oauth_token_secret']);

		$payload = $connection->get('account/verify_credentials', ['include_email' => 'true']);
		// don't forgot the qoutes over the true.

		return $payload;
	}

	/**
	 * @param $payload
	 */
	public function setPayload($payload)
	{
		$_SESSION['TwitterPayload'] = $payload;
		return ;
	}

	/**
	 *
	 */
	public function renderShortcode(){
		if (isset($_GET['oauth_token'])) {
			$payload = $this->getPayload();
			$this->setPayload($payload);
		}

		$data = false;
		if (isset($_SESSION['TwitterPayload'])) {
			$data = $_SESSION['TwitterPayload'];
		}

		if (!$data) {
			echo '<a href="' . $this->getUrl() . '">Sign In with Twitter</a>';

		}
		else {
			$payload = $_SESSION['TwitterPayload'];
			var_dump($payload);
			echo '<br> <a href="">Log Out!</a>';//TODO
		}
	}

}
