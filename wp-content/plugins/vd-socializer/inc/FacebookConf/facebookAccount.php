<?php
include_once 'wp-load.php';

$rawData =  get_metadata('user', get_current_user_id(), 'facebook_account');
$payload = $rawData[0];
?>
<div class = "account-box" >
	<h4>Account information</h4>
	<img src=" <?php echo $payload['user_img'] ?>" style="display: block;  margin-right: auto;margin-bottom: 5%; width: 100px; height: 100px">
	<p>Name: <?php echo $payload['name'] ?></p>
	<p>Email: <?php echo $payload['email'] ?></p>
	<p><a href="<?php echo PLUGIN_URL. '/inc/FacebookConf/facebookLogout.php'?>">Log Out from Facebook</a></p>
</div>
