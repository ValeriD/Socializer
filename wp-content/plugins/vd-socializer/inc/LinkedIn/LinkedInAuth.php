<?php


namespace Inc\LinkedIn;


class LinkedInAuth
{
	/**
	 * @var string
	 */
	protected $app_id = '86k9q6pmv36gk7';
	/**
	 * @var string
	 */
	protected $app_secret = 'doDYgWUdFfyNBCGA';
	/**
	 * @var string
	 */
	protected $callback = 'https://socializer.com/wp-content/plugins/vd-socializer/inc/LinkedIn/callback.php';
	/**
	 * @var int
	 */
	protected $csrf;
	/**
	 * @var array
	 */
	protected $scopes = 'r_emailaddress r_basicprofile r_liteprofile w_member_social rw_company_admin w_share';
	/**
	 * @var bool
	 */
	protected $ssl = false;

	/**
	 * LinkedInAuth constructor.
	 */
	public function __construct()
	{

		try {
			$this->csrf = random_int( 111111, 99999999999 );
		} catch ( \Exception $e ) {
			var_dump($e->getMessage());
		}

		add_shortcode('linkedIn', array($this,'renderShortcode'));
	}

	/**
	 * @return string
	 */
	public function getAuthUrl()
	{
		$_SESSION['linkedincsrf']  = $this->csrf;
		$result = 'https://www.linkedin.com/oauth/v2/authorization?response_type=code&client_id='. $this->app_id . '&redirect_uri='.$this->callback .'&state='. $this->csrf.'&scope='. $this->scopes;
		return  $result;
	}

	/**
	 * @param $code
	 *
	 * @return mixed
	 */
	public function getAccessToken($code)
	{
		$url = "https://www.linkedin.com/oauth/v2/accessToken";
		$params = [
			'client_id' => $this->app_id,
			'client_secret' => $this->app_secret,
			'redirect_uri' => $this->callback,
			'code' => $code,
			'grant_type' => 'authorization_code',
		];
		$response =''; //$this->curl($url,http_build_query($params), "application/x-www-form-urlencoded");
		$accessToken = json_decode($response)->access_token;
		return $accessToken;
	}

	/**
	 * @param $accessToken
	 *
	 * @return mixed
	 */
	public function getPerson($accessToken)
	{
		$url = "https://api.linkedin.com/v2/me?projection=(id,firstName,lastName,profilePicture(displayImage~:playableStreams))&oauth2_access_token=" . $accessToken;
		$params = [];
		$response = ''; //$this->curl($url,http_build_query($params), "application/x-www-form-urlencoded", false);
		$person = json_decode($response);
		return $person;
	}

	/**
	 * @return string
	 */
	public function renderShortcode(){
		$html = '<div>';
		if(isset($_SESSION['linkedInAccessToken'])){
			$profile = $this->getPerson($_SESSION['linkedInAccessToken']);
			$html.= '<p>' . $profile . '</p>';
		}else {
			$html = '<a href="' . $this->getAuthUrl() . '" >Sign In with LinkedIn</a>';
		}
		$html.='</div>';
		return $html;
	}
}