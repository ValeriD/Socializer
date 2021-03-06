<?php
include_once '../../vendor/autoload.php';
require '../../../../../wp-config.php';

use Inc\Facebook\FacebookAuth;


$facebook = new FacebookAuth();

if (isset($_GET['code'])) {

    $accessToken = $facebook->generateAccessToken();
    $facebook->getClient()->setDefaultAccessToken($_SESSION['facebook_access_token']);

    $userData = $facebook->getUserData();
    $facebook->saveUserData($userData);


    $posts = $facebook->getUserPosts();
    $facebook->savePosts($posts);
}
wp_redirect(home_url('accounts'));

