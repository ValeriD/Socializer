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

		if(isset($_REQUEST['oauth_verifier'], $_REQUEST['oauth_token']) && $_REQUEST['oauth_token']==$_SESSION['oauth_token']) {
			$requestToken = [];
			$requestToken['oauth_token'] = $_SESSION['oauth_token'];
			$requestToken['oauth_token_secret']  = $_SESSION['oauth_token_secret'];
			$connection                          = new TwitterOAuth( $this->customerKey, $this->customerSecret, $requestToken['oauth_token'], $requestToken['oauth_token_secret'] );
			try {
				$accessToken = $connection->oauth( "oauth/access_token", array( "oauth_verifier" => $_REQUEST['oauth_verifier'] ) );
			} catch ( TwitterOAuthException $e ) {
				var_dump($e->getMessage());
			}
			$_SESSION['access_token']    = $accessToken;
			var_dump( 'verified...' );
		}
		if ( ! isset( $_SESSION['access_token'] ) ) {
			var_dump( 'not set accessToken' );
			$connection                             = new TwitterOAuth( $this->customerKey, $this->customerSecret );
			try {
				$requsetToken = $connection->oauth( 'oauth/request_token', array( 'oauth_callback' => $this->callback ) );
			} catch ( TwitterOAuthException $e ) {
				var_dump($e->getMessage());
			}
			$_SESSION['oauth_token']        = $requsetToken['oauth_token'];
			$_SESSION['oauth_token_secret'] = $requsetToken['oauth_token_secret'];
			$url = $connection->url( 'oauth/authorize', array( 'oauth_token' => $requsetToken['oauth_token'] ) );
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