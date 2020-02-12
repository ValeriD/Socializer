<?php


namespace Inc\Base;



use Abraham\TwitterOAuth\TwitterOAuth;
use Abraham\TwitterOAuth\TwitterOAuthException;

class TwitterInit {


	private $customerKey= 'QJd21c6OVDluWLrTlWziAEtk8';
	private $customerSecret = '8Dz6DmAxE38uoojW99qSNgbae8mmZWayvSTK9y4v2n9jmyb8rP';
	private $callback = 'https://3f9d39c5.ngrok.io/socializer/accounts';


	public function register(){
		add_action('init', array($this, 'renderShortcode'));
		add_shortcode('twitter', array($this,'renderShortcode'));

	}

	public function renderShortcode(){
		if(!session_id()){
			session_start();
		}
		var_dump($_REQUEST['oauth_token']);

		var_dump($_SESSION['oauth_token']);

		//Checks if there is sent a verifier and compare oauth_tokens from the Request and the Session (they are different and I don't know why :)
		if(isset($_REQUEST['oauth_verifier'], $_REQUEST['oauth_token']) && $_REQUEST['oauth_token']==$_SESSION['oauth_token']) {
			//getting the oauth_token and its secret so we can get an access token
			$requestToken = [];
			$requestToken['oauth_token'] = $_SESSION['oauth_token'];
			$requestToken['oauth_token_secret']  = $_SESSION['oauth_token_secret'];
			$connection                          = new TwitterOAuth( $this->customerKey, $this->customerSecret, $requestToken['oauth_token'], $requestToken['oauth_token_secret'] );
			try {
				//getting the access token
				$accessToken = $connection->oauth( "oauth/access_token", array( "oauth_verifier" => $_REQUEST['oauth_verifier'] ) );
			} catch ( TwitterOAuthException $e ) {
				var_dump($e->getMessage());
			}
			//saving it in the session
			$_SESSION['access_token']    = $accessToken;
			var_dump( 'verified...' );
		}
		//if token not set in session => the user visit the site for the first time
		if ( ! isset( $_SESSION['access_token'] ) ) {
			var_dump( 'not set accessToken' );
			//getting the oauth token
			$connection = new TwitterOAuth( $this->customerKey, $this->customerSecret );
			try {
				$requestToken = $connection->oauth( 'oauth/request_token', array( 'oauth_callback' => $this->callback ) );
			} catch ( TwitterOAuthException $e ) {
				var_dump($e->getMessage());
			}
			//saving the oauth token in the session
			$_SESSION['oauth_token']        = $requestToken['oauth_token'];
			$_SESSION['oauth_token_secret'] = $requestToken['oauth_token_secret'];

			//getting the authorization url
			$url = $connection->url( 'oauth/authorize', array( 'oauth_token' => $requestToken['oauth_token'] ) );

			//Button for the logging
			$html = '<div>';
			$html .= '<a href="' . $url . '" class="btn">Login with Twitter</a>';

			$html .= '</div>';

			return $html;
		}
		else{
			$accessToken = $_SESSION['access_token'];
			$connection = new TwitterOAuth($this->customerKey, $this->customerSecret, $accessToken['oauth_token'], $accessToken['oauth_token_secret']);
			$user = $connection->get("account/verify_credentials");
		}
	}


}