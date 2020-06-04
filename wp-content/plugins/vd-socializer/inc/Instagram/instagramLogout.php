<?php
require_once '../../../../../wp-load.php';

unset($_SESSION['instagram_access_token']);
wp_redirect(home_url('/accounts'));