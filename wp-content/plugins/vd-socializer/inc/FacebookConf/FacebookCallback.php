<?php
include_once '../../vendor/autoload.php';
require 'C:\xampp\htdocs\socializer\wp-config.php';

use Inc\FacebookConf\FacebookAuth;


$facebook = new FacebookAuth();
$facebook->apiInit();

if (isset($_GET['code'])) {

    $accessToken = $facebook->generateAccessToken();
}
wp_redirect(home_url('accounts'));
?>
