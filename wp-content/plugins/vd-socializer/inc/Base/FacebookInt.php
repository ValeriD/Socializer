<?php
/**
 * @package vd-socializer
 */

namespace Inc\Base;








use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;

class FacebookInt{
	/**
	 * Facebook APP ID
	 *
	 * @var string
	 */
	private $app_id = '2187669774872877';

	/**
	 * Facebook APP Secret
	 *
	 * @var string
	 */
	private $app_secret = 'ce5ec9e50a0d4a39629208c6facdc0b7';

	/**
	 * Callback URL used by the API
	 *
	 * @var string
	 */
	private $callback_url = 'https://a6322542.ngrok.io/socializer/wp-admin/admin-ajax.php?action=socializer_facebook';


	private $redirect_url = 'https://a6322542.ngrok.io/socializer/accounts';

	private $access_token;

	private $facebook_details;




	/**
	 * Facebook constructor.
	 */
	public function register() {
		if(!session_id()) {
			session_start();
		}

		include ( PLUGIN_PATH . 'sdks\facebook_sdk\autoload.php');

		add_action( 'init' , array($this, 'startSession'));


		add_shortcode('facebook', array($this,'renderShortcode'));


	}

	public function startSession(){
		if(!session_id()){
			session_start();
		}
	}

	public function renderShortcode() {
		$html='';
		// No need for the button if the user is already logged

		$fb = $this->initApi();


		$helper = $fb->getRedirectLoginHelper();


		$permissions = [ 'email' ];

		if ( isset( $_SESSION['facebook_access_token'] ) ) {
			$this->access_token = $_SESSION['facebook_access_token'];

		} else {
			try {

				$this->access_token = $helper->getAccessToken();
			} catch ( FacebookSDKException $e ) {
				echo 'Graph returned error: ' . $e->getMessage();
			}
		}


		if ( isset( $this->access_token ) ) {
			if ( !isset( $_SESSION['facebook_access_token'] ) ) {
				$_SESSION['facebook_access_token'] = $this->access_token;

				$oAuth2Client = $fb->getOAuth2Client();
				$tokenMeta = $oAuth2Client->debugToken($this->access_token);

//				try {
//					$longLivedAccessToken = $oAuth2Client->getLongLivedAccessToken( $_SESSION['facebook_access_token'] );
//				} catch ( FacebookSDKException $e ) {
//					echo $e->getMessage();
//				}

				//$_SESSION['facebook_access_token'] = $longLivedAccessToken;


			}
			$fb->setDefaultAccessToken( $_SESSION['facebook_access_token'] );



			if ( isset( $_GET['code'] ) ) {
				header( 'Location: ./' );
			}

			try {
				$profile_request = $fb->get( '/me?fields=name,first_name,last_name,email' );

				$profile = $profile_request->getGraphNode()->asArray();
			} catch ( FacebookSDKException $e ) {
				echo $e->getMessage();
				session_destroy();
				header( 'Location: ./' );
			}


			return $html;
		}


	else{

			// Button markup
			$loginUrl = $helper->getLoginUrl('https://localhost/socializer/accounts', $permissions);
			$html = '<div>';
			$html .= '<a href="'.$loginUrl.'" class="btn">Login with Facebook</a>';

			$html .= '</div>';


		// Write it down
			return $html;

		}


}



	/**
	 * Init the API Connection
	 *
	 * @return
	 *
	 */
	private function initApi() {

		try {
			$facebook = new Facebook( [
				'app_id'                  => $this->app_id,
				'app_secret'              => $this->app_secret,
				'default_graph_version'   => 'v2.4',
				'persistent_data_handler' => 'session'
			] );
		} catch ( FacebookSDKException $e ) {
			var_dump($e->getMessage());
		}

		return $facebook;

	}

}