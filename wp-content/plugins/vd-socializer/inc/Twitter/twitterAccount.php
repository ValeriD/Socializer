<?php
$payload = json_decode(json_encode($_SESSION['TwitterPayload']), true);
?>
<div class = "account-box" >
	<h4>Account information</h4>
	<p>Name: <?php echo $payload['name'] ?></p>
	<p>Twitter username: <?php echo $payload['screen_name'] ?></p>
	<p>Location: <?php echo $payload['location'] ?></p>
	<p>User description: <?php echo $payload['description'] ?></p>
	<p><a href="<?php echo PLUGIN_URL. '/inc/Twitter/twitterLogout.php'?>">Log Out from Twitter</a></p>
</div>
