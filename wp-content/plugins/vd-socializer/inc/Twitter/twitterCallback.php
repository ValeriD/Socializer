<?php

include_once '../../vendor/autoload.php';
require '../../../../../wp-config.php';

use Inc\Twitter\TwitterAuth;

$twitter = new TwitterAuth();

if ( isset( $_GET['oauth_token'] ) ) {
	$twitter->storeToken();

	$userData = $twitter->getUserData();
	$posts = $twitter->getUserPosts();

	$twitter->saveUserData( $userData );
	$twitter->savePosts($posts);
}
unset($_SESSION['twitter_auth']);
wp_redirect(home_url('accounts'));

