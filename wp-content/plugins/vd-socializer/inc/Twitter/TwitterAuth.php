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
	 * TwitterAuth constructor.
	 *
	 */
	public function __construct()
	{

		if(get_option('vd_twitter_app')!=="" && get_option('vd_twitter_secret')!=="") {
			SocialNetwork::__construct(home_url('/wp-content/plugins/vd-socializer/inc/Twitter/twitterCallback.php'), get_option('vd_twitter_app'),  get_option('vd_twitter_secret'));

			$this->setClient( new TwitterOAuth($this->getAppId(),$this->getAppSecret()));

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
			return $this->getClient()->oauth('oauth/request_token', ['oauth_callback' => $this->getClientCallback()]);
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

		return $this->getClient()->url('oauth/authorize', ['oauth_token' => $_SESSION['oauth_token'] ]);
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

	public function storeToken() {

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
		$_SESSION['twitter_access_token'] = $accessToken;

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

		return $payload;
	}

	/**
	 * @param $payload
	 */
	public function saveUserData($payload)
	{
		$_SESSION['TwitterPayload'] = $payload;

		update_user_meta(get_current_user_id(), 'twitter_account', $this->serializeUserData($payload));

		return;
	}

	protected function serializeUserData($payload){
		$data =  json_decode(json_encode($payload),true);
		return array(
			'username' => $data['screen_name'],
			'name' => $data['name'],
			'description' => $data['description'],
			'location' => $data['location'],
			'user_img' => $data['profile_image_url']
		);
	}


	public function getUserPosts(){
		$accessToken = $this->getAccessToken();

		$connection = new TwitterOAuth($this->getAppId(), $this->getAppSecret(), $accessToken['oauth_token'], $accessToken['oauth_token_secret']);

		return $connection->get('statuses/user_timeline');
	}

	public function serializeDataForDB($postInfo,Post $post){
		$postData = json_decode(json_encode($postInfo), true);

		$post->setAuthor(get_current_user_id());
		$post->setContent($postData['text']);

		$post->setMetaData('social_id', $postData['id']);
		$media = $postData['entities']['media'][0]['media_url'];
		$post->setMetaData('post_img', $media);
		$post->setMetaData('post_likes', $postData['favorite_count']);
		$post->setMetaData('post_shares', $postData['retweet_count']);
		$date = new \DateTime($postData['created_at']);
		$post->setMetaData('post_date', $date);
	}
	protected function serializeDataForBQ(Post $post){
		return [
			'social_id' => $post->getMetaData('social_id'),
			'post_text' => $post->getTitle(),
			'post_author' => get_current_user_id(),
			'post_category' => 'Twitter',
			'post_img' => $post->getMetaData('post_img'),
			'post_likes' => $post->getMetaData('post_likes'),
			'post_shares' => $post->getMetaData('post_shares'),
			'post_date' => $post->getMetaData('post_date')
		];
	}

	/**
	 *
	 */
	public function renderShortcode(){
		if(is_user_logged_in()) {



			if ( isset($_SESSION['twitter_access_token']) ) {
				include( PLUGIN_PATH . '/inc/Twitter/twitterAccount.php' );

			} else {
				echo '<p><a href="' . $this->getLoginUrl() . '">Sign In with Twitter</a></p>';
			}
		}
	}






}
