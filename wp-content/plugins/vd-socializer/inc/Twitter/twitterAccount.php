<?php
include_once 'wp-load.php';
//$payload = json_decode(json_encode($_SESSION['TwitterPayload']), true);
$rawData =  get_metadata('user', get_current_user_id(), 'twitter_account');
$payload = $rawData[0];
?>
<div class = "account-box" >
	<h4>Account information</h4>
	<p>Name: <?php echo $payload['name'] ?></p>
	<p>Twitter username: <?php echo $payload['username'] ?></p>
	<p>Location: <?php echo $payload['location'] ?></p>
	<p>User description: <?php echo $payload['description'] ?></p>
	<p><a href="<?php echo PLUGIN_URL. '/inc/Twitter/twitterLogout.php'?>">Log Out from Twitter</a></p>
</div>
