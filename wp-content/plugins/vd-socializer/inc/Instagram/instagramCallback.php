<?php

include_once '../../vendor/autoload.php';
require 'C:\xampp\htdocs\socializer\wp-config.php';

use Inc\Instagram\InstagramAuth;

$instagram = new InstagramAuth();
if(isset($_GET['code'])){
	$instagram->generateAccessToken();
	$instagram->getUserData();

}

