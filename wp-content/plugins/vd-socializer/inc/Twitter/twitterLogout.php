<?php
require_once 'C:\xampp\htdocs\socializer\wp-load.php';

unset($_SESSION['twitter_auth']);
unset($_SESSION['twitter_access_token']);
wp_redirect(home_url('/accounts')); //TODO
