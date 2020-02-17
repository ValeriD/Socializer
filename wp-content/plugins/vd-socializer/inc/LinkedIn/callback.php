<?php

use Inc\LinkedIn\LinkedInAuth;

$linkedin = new LinkedInAuth();

if ($_GET['state'] != $_SESSION['linkedincsrf']) {
	die("INVALID REQUEST");
}

$accessToken = $linkedin->getAccessToken($_GET['code']);
if (!$accessToken) {
	die("NO ACCESS TOKEN FOUND. LOGIN AGAIN");
}

$_SESSION['linkedInAccessToken'] = $accessToken;

header("location: "); //TODO
die();
