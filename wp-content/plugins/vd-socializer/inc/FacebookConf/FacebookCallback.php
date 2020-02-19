<?php

use Inc\FacebookConf\FacebookAuth;

$facebook = new FacebookAuth();

if (isset($_GET['code'])) {

	$graph = $facebook->getGraph();
	$_SESSION['payload'] = $graph; //saving facebook fetched data into session.
}?>


<div>
	<?php

		$payload = $_SESSION['payload']; // payload is an array.
		var_dump($payload);
		echo '<br> <a href="/logout.php">Log Out!</a>';


	?>
</div>

