<?php
/**
 * @package vd-socializer
 */

namespace Inc\Base;








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
	private $callback_url = 'http://localhost/socializer/accounts';

	/**
	 * Facebook constructor.
	 */
	public function register() {
		if(!session_id()) {
			session_start();
		}
		include ( PLUGIN_PATH . 'facebook_sdk\autoload.php');
		add_shortcode('facebook', array($this,'renderShortcode'));

	}

	public function renderShortcode() {

		// No need for the button if the user is already logged
		if(is_user_logged_in())
			return;

		// Different labels according to whether the user is allowed to register or not
		if (get_option( 'users_can_register' )) {
			$button_label = __('Login or Register with Facebook', 'alkaweb');
		} else {
			$button_label = __('Login with Facebook', 'alkaweb');
		}

		// Button markup
		$html = '<div id="socializer-wrapper">';
		$html .= '<a href="'.$this->getLoginUrl().'" class="btn" id="socializer-facebook-button">'.$button_label.'</a>';
		$html .= '</div>';

		// Write it down
		return $html;

	}

	/**
	 * Init the API Connection
	 *
	 * @return Facebook
	 *
	 */
	public function initApi() {

		$facebook = new Facebook([
			'app_id' => $this->app_id,
			'app_secret' => $this->app_secret,
			'default_graph_version' => 'v2.2',
			'persistent_data_handler' => 'session'
		]);

		return $facebook;

	}
	/**
	 * Login URL to Facebook API
	 *
	 * @return string
	 */
	private function getLoginUrl() {



		$fb = $this->initApi();

		$helper = $fb->getRedirectLoginHelper();



		$url = $helper->getLoginUrl($this->callback_url);

		return esc_url($url);

	}


}