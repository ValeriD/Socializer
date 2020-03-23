<?php

use Inc\Admin\AdminMenu;
if($_POST['vd_hidden'] == 'Y') {

    $facebook_app = $_POST['vd_facebook_app'];
    update_option('vd_facebook_app', $facebook_app);

    $facebook_secret = $_POST['vd_facebook_secret'];
    update_option('vd_facebook_secret', $facebook_secret);

	$twitter_app = $_POST['vd_twitter_app'];
	update_option('vd_twitter_app', $twitter_app);

	$twitter_secret = $_POST['vd_twitter_secret'];
	update_option('vd_twitter_secret', $twitter_secret);

        ?>
        <div class="updated"><p><strong><?php _e('Options saved.' ); ?></strong></p></div>
    <?php
}
else{
    $dbhost = get_option('vd_dbhost');
    $dbname = get_option('vd_dbname');
    $dbuser = get_option('vd_dbuser');
    $dbpwd = get_option('vd_dbpwd');
    $facebook_app = get_option('vd_facebook_app');
    $facebook_secret = get_option('vd_facebook_secret');
    $twitter_app = get_option('vd_twitter_app');
    $twitter_secret = get_option('vd_twitter_secret');
}



?>

<div class="wrap">
    <?php    echo "<h2>" . __( 'Socializer Settings', 'vd_trdom' ) . "</h2>"; ?>

    <form name="vd_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
        <input type="hidden" name="vd_hidden" value="Y">

        <?php    echo "<h4>" . __( 'Facebook Settings', 'vd_trdom' ) . "</h4>"; ?>
        <p><?php _e("Facebook App Id: " ); ?><input type="text" name="vd_facebook_app" value="<?php echo $facebook_app; ?>" size="50"><?php _e("ex: 2187669774872877" ); ?></p>
        <p><?php _e("Facebook App Secret: " ); ?><input type="text" name="vd_facebook_secret" value="<?php echo $facebook_secret; ?>" size="100"><?php _e("ex: ce5ec9e50a0d4a39629208c6facdc0b7" ); ?></p>

	    <?php    echo "<h4>" . __( 'Twitter Settings', 'vd_trdom' ) . "</h4>"; ?>
	    <p><?php _e("Twitter App Id: " ); ?><input type="text" name="vd_twitter_app" value="<?php echo $twitter_app; ?>" size="50"><?php _e("ex: QJd21c6OVDluWLrTlWziAEtk8" ); ?></p>
	    <p><?php _e("Twitter App Secret: " ); ?><input type="text" name="vd_twitter_secret" value="<?php echo $twitter_secret; ?>" size="100"><?php _e("ex: 8Dz6DmAxE38uoojW99qSNgbae8mmZWayvSTK9y4v2n9jmyb8rP" ); ?></p>

        <p class="submit">
        <input type="submit" name="Submit" value="<?php _e('Update Options', 'vd_trdom' ) ?>" />
        </p>
    </form>
</div>

