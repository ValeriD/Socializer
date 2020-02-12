/**
* Login URL to Facebook API
*
* @return string
*/
private function getLoginUrl() {
if (!session_id()) {
session_start();
}
var_dump($_SESSION);


$fb = $this->initApi();


$helper = $fb->getRedirectLoginHelper();

$url = $helper->getLoginUrl($this->callback_url);
var_dump($url);
return esc_url($url);

}

/**
*
*/
public  function apiCallback(){

if (!session_id()) {
session_start();
}

$fb = $this->initApi();
$this->redirect_url =( isset( $_SESSION[ 'socializer_facebook_url' ] ) ) ? $_SESSION[ 'socializer_facebook_url' ] : home_url();

var_dump('before get token');
$this->access_token = $this->getToken();

$this->facebook_details = $this->getUserDetails($fb);
var_dump($this->facebook_details);

if(!$this->loginUser()){
$this->createUser();
}

header('Location: ' .  $this->redirect_url, true );
//		var_dump($this->access_token);
//
die();

}

private function getToken(  ) {

$_SESSION['FBRLH_state'] = $_GET['state'];

var_dump('in getToken');
$message='';
$fb = $this->initApi();
//Getting helper
$helper = $fb->getRedirectLoginHelper();
var_dump('in getToken');
//Trying to get AccessToken
try {
$access_token = $helper->getAccessToken();

} catch(FacebookResponseException $e) {
var_dump($e->getMessage());
$error = __('Graph returned an error: ','socializerweb'). $e->getMessage();
$message = array(
'type' => 'error',
'content' => $error
);
} catch ( FacebookSDKException $e ) {
var_dump($e->getMessage());
$error = __('Facebook SDK returned an error: ','socializerweb'). $e->getMessage();
$message = array(
'type' => 'error',
'content' => $error
);
}
var_dump($access_token->getValue());

//Checks if the try was successful
if(!isset($access_token)){
$_SESSION['socializer_facebook_message'] = $message;
//header('Location: ' . $this -> redirect_url, true);
die();
}
//Returning the AccessToken
return $access_token->getValue();
}

private function getUserDetails( Facebook $fb ) {

try {
$response = $fb->get( '/me?fields=id,name,first_name,last_name,email,link', $this->access_token );
}  catch(FacebookResponseException $e) {
$error = __('Graph returned an error: ','socializerweb'). $e->getMessage();
$message = array(
'type' => 'error',
'content' => $error
);
} catch ( FacebookSDKException $e ) {
$error = __('Facebook SDK returned an error: ','socializerweb'). $e->getMessage();
$message = array(
'type' => 'error',
'content' => $error
);
}

//If we caught error
if(isset($message)){
//Report the error
$_SESSION['socializer_facebook_message'] = $message;
//Redirect
header('Location: ' . $this -> redirect_url, true);
die();
}
return $response->getGraphUser();

}

private function loginUser() {
$wp_users = get_users(array(
'meta_key'     => 'alka_facebook_id',
'meta_value'   => $this->facebook_details['id'],
'number'       => 1,
'count_total'  => false,
'fields'       => 'id',
));

if(empty($wp_users[0])) {
return false;
}

// Log the user ?
wp_set_auth_cookie( $wp_users[0] );

}

private function createUser() {
$fb_user = $this->facebook_details;
$username = sanitize_user(str_replace(' ', '_', strtolower($this->facebook_details['name'])));
$new_user = wp_create_user($username, wp_generate_password(), $fb_user['email']);
if(is_wp_error($new_user)) {
// Report our errors
$_SESSION['socializer_facebook_message'] = $new_user->get_error_message();
// Redirect
header("Location: ".$this->redirect_url, true);
die();
}

// Setting the meta
update_user_meta( $new_user, 'first_name', $fb_user['first_name'] );
update_user_meta( $new_user, 'last_name', $fb_user['last_name'] );
update_user_meta( $new_user, 'user_url', $fb_user['link'] );
update_user_meta( $new_user, 'alka_facebook_id', $fb_user['id'] );

// Log the user ?
wp_set_auth_cookie( $new_user );
}

