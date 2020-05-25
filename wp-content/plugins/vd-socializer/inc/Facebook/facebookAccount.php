<?php
include_once 'wp-load.php';

$rawData =  get_metadata('user', get_current_user_id(), 'facebook_account');
$payload = $rawData[0];
?>
<div class = "account-box" >
	<h4>Account information</h4>
    <?php  if(isset($payload['user_img'])){?>
	<img src=" <?php echo $payload['user_img'] ?>" style="display: block;  margin-right: auto;margin-bottom: 5%; width: 100px; height: 100px">
    <?php }
        if(isset($payload['name'])){
    ?>
	<p>Name: <?php echo $payload['name'] ?></p>
     <?php }
        if(isset($payload['email'])){
	 ?>
	<p>Email: <?php echo $payload['email'] ?></p>
    <?php }
        if(isset($payload['hometown'])){
    ?>
    <p>Hometown: <?php echo $payload['hometown'] ?></p>
    <?php }
        if(isset($payload['birthday'])){
    ?>
    <p>Birthday: <?php echo $payload['birthday'] ?></p>
        <?php }  ?>
	<p><a href="<?php echo PLUGIN_URL. '/inc/Facebook/facebookLogout.php'?>">Log Out from Facebook</a></p>
</div>
