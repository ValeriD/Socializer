<?php
require_once '../../../../../wp-load.php';

unset($_SESSION['twitter_auth']);
unset($_SESSION['twitter_access_token']);
wp_redirect(home_url('/accounts')); //TODO
