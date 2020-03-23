<?php


namespace Inc\Base;


abstract class SocialNetwork{

	private $clientCallback;
	private $client;
	private $app_id;
	private $app_secret;
	private $accessToken;
	private $bqClient;
	private $socialNetwork;

	public function __construct( $socialNetwork ) {
		if ( get_option( 'vd_'. strtolower($socialNetwork) .'_app' ) and get_option( 'vd_'. strtolower($socialNetwork) .'_secret' )  ) {
			$this->clientCallback = home_url('/wp-content/plugins/vd-socializer/inc/'. $socialNetwork .'/'. strtolower($socialNetwork) .'Callback.php');
			$this->app_id         = get_option( 'vd_'. strtolower($socialNetwork) .'_app' );
			$this->app_secret     = get_option( 'vd_'. strtolower($socialNetwork) .'_secret' );
			$this->socialNetwork = $socialNetwork;

			add_shortcode( strtolower($socialNetwork), array( $this, 'renderShortcode' ) );

			$this->initialize();

			if(class_exists('VDBigQuery')) {
				$this->bqClient = new \VDBigQuery();
			}
		}
	}

	/**
	 * @return mixed
	 */
	public function getClient() {
		return $this->client;
	}

	/**
	 * @param mixed $client
	 */
	public function setClient( $client ) {
		$this->client = $client;
	}

	/**
	 * @return string
	 */
	public function getClientCallback() {
		return $this->clientCallback;
	}

	/**
	 * @param string $clientCallback
	 */
	public function setClientCallback( $clientCallback ) {
		$this->clientCallback = $clientCallback;
	}

	/**
	 * @return mixed
	 */
	public function getAppId() {
		return $this->app_id;
	}

	/**
	 * @param mixed $app_id
	 */
	public function setAppId( $app_id ) {
		$this->app_id = $app_id;
	}

	/**
	 * @return mixed
	 */
	public function getAppSecret() {
		return $this->app_secret;
	}

	/**
	 * @param mixed $app_secret
	 */
	public function setAppSecret( $app_secret ) {
		$this->app_secret = $app_secret;
	}

	/**
	 * @return mixed
	 */
	public function getAccessToken() {
		return $this->accessToken;
	}

	/**
	 * @param mixed $accessToken
	 */
	public function setAccessToken( $accessToken ) {
		$this->accessToken = $accessToken;
	}
	/**
	 * @return \VDBigQuery
	 */
	public function getBqClient() {
		return $this->bqClient;
	}

	/**
	 * @param \VDBigQuery $bqClient
	 */
	public function setBqClient( $bqClient ) {
		$this->bqClient = $bqClient;
	}

	/**
	 * @return mixed
	 */
	public function getSocialNetwork() {
		return $this->socialNetwork;
	}

	/**
	 * @param mixed $socialNetwork
	 */
	public function setSocialNetwork( $socialNetwork ) {
		$this->socialNetwork = $socialNetwork;
	}

	protected abstract function initialize();

	protected abstract function generateAccessToken();

	protected abstract function getLoginUrl();

	public abstract function getUserData();
	public abstract function getUserPosts();

	protected abstract function serializeUserData($payload);
	protected abstract function serializeDataForDB($postData,Post $post);




	public function renderShortcode(){
		if(is_user_logged_in()) {
			if ( isset($_SESSION[strtolower($this->getSocialNetwork()) .'_access_token']) ) {

				include( PLUGIN_PATH . '/inc/'. $this->getSocialNetwork() .'/'. strtolower($this->getSocialNetwork()) .'Account.php' );

			} else {
				echo '<p><a href="' . $this->getLoginUrl() . '">Sign In with '. $this->getSocialNetwork() .'</a></p>';
			}
		}else{
			return ;
		}
	}

	public function saveUserData($payload)
	{
		update_user_meta(get_current_user_id(), strtolower($this->getSocialNetwork()) . '_account', $this->serializeUserData($payload));
	}

	public function savePosts($payload){
		foreach($payload as $post){
			$this->savePost($post);
		}
	}
	protected function savePost($postData){
		$post = new Post();
		$this->serializeDataForDB($postData, $post);
		$saved = $post->savePost();

		if(class_exists('VDBigQuery') and $saved) {
			$this->bqClient->addInTable( $this->getBqClient()->getTableId(), $this->getBqClient()->getDatasetId(), $this->serializeDataForBQ( $post ) );
		}
	}
	private  function serializeDataForBQ(Post $post){
		return [
			'social_id' => $post->getMetaData('social_id'),
			'post_text' => $post->getContent(),
			'post_author' => get_current_user_id(),
			'post_category' => $this->getSocialNetwork(),
			'post_img' => $post->getMetaData('post_img'),
			'post_likes' => $post->getMetaData('post_likes'),
			'post_shares' => $post->getMetaData('post_shares'),
			'post_date' => $post->getMetaData('post_date')
		];
	}
}