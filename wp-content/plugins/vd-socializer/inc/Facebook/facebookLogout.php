<?php
require_once '../../../../../wp-load.php';

unset($_SESSION['facebook_access_token']);
wp_redirect(home_url('/accounts'));