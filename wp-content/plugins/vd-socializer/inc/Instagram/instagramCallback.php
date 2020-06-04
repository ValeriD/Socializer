<?php

include_once '../../vendor/autoload.php';
require '../../../../../wp-config.php';

use Inc\Instagram\InstagramAuth;

$instagram = new InstagramAuth();
if(isset($_GET['code'])){
	$instagram->generateAccessToken();

	$instagram->saveUserData($instagram->getUserData());

	var_dump($instagram->getUserPosts());
	$instagram->savePosts($instagram->getUserPosts());



}

wp_redirect(home_url('accounts'));


