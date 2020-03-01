<?php


namespace Inc\Twitter;


use Abraham\TwitterOAuth\TwitterOAuth;
use Abraham\TwitterOAuth\TwitterOAuthException;
use Inc\Base\Post;
use Inc\Base\SocialNetwork;

/**
 * Class TwitterAuth
 * @package Inc\Twitter
 */

class TwitterAuth extends SocialNetwork {
	/**
	 * @var TwitterOAuth
	 */
	protected $client;


	/**
	 * TwitterAuth constructor.
	 *
	 * @param TwitterOAuth $client
	 */
	public function __construct()
	{

		if(get_option('vd_twitter_app')!=="" && get_option('vd_twitter_secret')!=="") {
			SocialNetwork::__construct(home_url('/accounts'), get_option('vd_twitter_app'),  get_option('vd_twitter_secret'));

			$this->client = new TwitterOAuth($this->getAppId(),$this->getAppSecret());
			add_shortcode('twitter', array($this, 'renderShortcode'));
		}
	}


	/**
	 * Function for generating the acceess token
	 * @return bool|array
	 * @throws \Abraham\TwitterOAuth\TwitterOAuthException
	 */
	protected function generateAccessToken()
	{
		if (!isset($_SESSION['twitter_auth'])) {
			return $this->client->oauth('oauth/request_token', ['oauth_callback' => $this->getClientCallback()]);
		}

		return false;
	}

	/**
	 * Saving the access token in the session
	 * @return bool
	 * @throws \Abraham\TwitterOAuth\TwitterOAuthException
	 */
	protected function storeUnverifiedToken()
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
	public function getLoginUrl()
	{
		try {
			$token = $this->storeUnverifiedToken();
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

	protected function storeToken() {

		$requestToken = $this->verifyToken();
		if (!$this->verifyToken()) {
			return false;
		}

		$connection = new TwitterOAuth($this->getAppId(),$this->getAppSecret(), $requestToken['oauth_token'], $requestToken['oauth_token_secret']);


		try {
			$accessToken = $connection->oauth( 'oauth/access_token', [ 'oauth_verifier' => $_REQUEST['oauth_verifier'] ] );
		} catch ( TwitterOAuthException $e ) {
			var_dump('TwitterOAuthException: ' . $e->getMessage());
		}
		$this->setAccessToken($accessToken);

	}

	/**
	 * @return array|bool|object
	 *
	 */
	public function getUserData()
	{
		$accessToken = $this->getAccessToken();

		$connection = new TwitterOAuth($this->getAppId(), $this->getAppSecret(), $accessToken['oauth_token'], $accessToken['oauth_token_secret']);

		$payload = $connection->get('account/verify_credentials', ['include_email' => 'true']);
		// don't forgot the qoutes over the true.

		return $payload;
	}

	/**
	 * @param $payload
	 */
	public function saveUserData($payload)
	{
		$_SESSION['TwitterPayload'] = $payload;

		update_user_meta(get_current_user_id(), 'twitter_account', $this->getUserDataInArray());

		return ;
	}
	private function getUserDataInArray(){
		$data =  json_decode(json_encode($_SESSION['TwitterPayload']),true);
		return array(
			'username' => $data['screen_name'],
			'name' => $data['name'],
			'description' => $data['description'],
			'location' => $data['location']
		);
	}


	private function getUserPosts(){
		$accessToken = $this->getAccessToken();

		$connection = new TwitterOAuth($this->getAppId(), $this->getAppSecret(), $accessToken['oauth_token'], $accessToken['oauth_token_secret']);

		return $connection->get('statuses/user_timeline');
	}
	private function savePosts($payload){
		foreach($payload as $post){
			$postData = json_decode(json_encode($post), true);
			$this->savePost($postData);

		}
	}
	private function savePost($postData){
		//var_dump($postData);
		$post = new Post($postData['id']);
		$post->setAuthor(get_current_user_id());
		$post->setTitle($postData['text']);
		$post->setContent($postData['text']);
		$media = $postData['entities']['media'][0]['media_url'];
		//var_dump($media);
		$post->setImagUrl($media);
		$post->savePost();


	}

	/**
	 *
	 */
	public function renderShortcode(){
		if(is_user_logged_in()) {
			if ( isset( $_GET['oauth_token'] ) ) {
				$this->storeToken();

				$userData = $this->getUserData();
				$posts = $this->getUserPosts();
				//var_dump($posts);
				$this->saveUserData( $userData );
				$this->savePosts($posts);
			}

			$data = false;
			if ( isset( $_SESSION['TwitterPayload'] ) ) {
				$data = $_SESSION['TwitterPayload'];
			}

			if ( ! $data ) {
				echo '<p><a href="' . $this->getLoginUrl() . '">Sign In with Twitter</a></p>';

			} else {
				include( PLUGIN_PATH . '/inc/Twitter/twitterAccount.php' );
			}
		}
	}






}
