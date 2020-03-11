<?php
include_once '../../vendor/autoload.php';
require 'C:\xampp\htdocs\socializer\wp-config.php';
use Inc\FacebookConf\FacebookAuth;

$facebook = new FacebookAuth();
$facebook->facebookLogOut();
wp_redirect(home_url());