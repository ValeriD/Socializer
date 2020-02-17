<?php

use Inc\FacebookConf\FacebookAuth;

$facebook = new FacebookAuth();
$facebook->facebookLogOut();
header('location: '); //TODO
