<?php
if($_POST['vd_hidden'] == 'Y') {
    //Form data sent
    $dbhost = $_POST['vd_dbhost'];
    update_option('vd_dbhost', $dbhost);

    $dbname = $_POST['vd_dbname'];
    update_option('vd_dbname', $dbname);

    $dbuser = $_POST['vd_dbuser'];
    update_option('vd_dbuser', $dbuser);

    $dbpwd = $_POST['vd_dbpwd'];
    update_option('vd_dbpwd', $dbpwd);

    $facebook_app = $_POST['vd_facebook_app'];
    update_option('vd_facebook_app', $facebook_app);

    $facebook_secret = $_POST['vd_facebook_secret'];
    update_option('vd_facebook_secret', $facebook_secret);

	$twitter_app = $_POST['vd_twitter_app'];
	update_option('vd_twitter_app', $twitter_app);

	$twitter_secret = $_POST['vd_twitter_secret'];
	update_option('vd_twitter_secret', $twitter_secret);

	$linkedin_app = $_POST['vd_linkedin_app'];
	update_option('vd_linkedin_app', $linkedin_app);

	$linkedin_secret = $_POST['vd_linkedin_secret'];
	update_option('vd_linkedin_secret', $linkedin_secret);
        ?>
        <div class="updated"><p><strong><?php _e('Options saved.' ); ?></strong></p></div>
    <?php
}
else{
    $dbhost = get_option('vd_dbhost');
    var_dump($dbhost);
    $dbname = get_option('vd_dbname');
    $dbuser = get_option('vd_dbuser');
    $dbpwd = get_option('vd_dbpwd');
    $facebook_app = get_option('vd_facebook_app');
    $facebook_secret = get_option('vd_facebook_secret');
    $twitter_app = get_option('vd_twitter_app');
    $twitter_secret = get_option('vd_twitter_secret');
    $linkedin_app = get_option('vd_linkedin_app');
    $linkedin_secret = get_option('vd_linkedin_secret');
}



?>

<div class="wrap">
    <?php    echo "<h2>" . __( 'Socializer Database Settings', 'vd_trdom' ) . "</h2>"; ?>

    <form name="vd_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
        <input type="hidden" name="vd_hidden" value="Y">
        <?php    echo "<h4>" . __( 'Socializer Database Settings', 'vd_trdom' ) . "</h4>"; ?>
        <p><?php _e("Database host: " ); ?><input type="text" name="vd_dbhost" value="<?php echo $dbhost; ?>" size="20"><?php _e(" ex: localhost" ); ?></p>
        <p><?php _e("Database name: " ); ?><input type="text" name="vd_dbname" value="<?php echo $dbname; ?>" size="20"><?php _e(" ex: socializer" ); ?></p>
        <p><?php _e("Database user: " ); ?><input type="text" name="vd_dbuser" value="<?php echo $dbuser; ?>" size="20"><?php _e(" ex: root" ); ?></p>
        <p><?php _e("Database password: " ); ?><input type="text" name="vd_dbpwd" value="<?php echo $dbpwd; ?>" size="20"><?php _e(" ex: " ); ?></p>
        <hr />
        <?php    echo "<h4>" . __( 'Facebook Settings', 'vd_trdom' ) . "</h4>"; ?>
        <p><?php _e("Facebook App Id: " ); ?><input type="text" name="vd_facebook_app" value="<?php echo $facebook_app; ?>" size="50"><?php _e("ex: 2187669774872877" ); ?></p>
        <p><?php _e("Facebook App Secret: " ); ?><input type="text" name="vd_facebook_secret" value="<?php echo $facebook_secret; ?>" size="100"><?php _e("ex: ce5ec9e50a0d4a39629208c6facdc0b7" ); ?></p>

	    <?php    echo "<h4>" . __( 'Twitter Settings', 'vd_trdom' ) . "</h4>"; ?>
	    <p><?php _e("Twitter App Id: " ); ?><input type="text" name="vd_twitter_app" value="<?php echo $twitter_app; ?>" size="50"><?php _e("ex: QJd21c6OVDluWLrTlWziAEtk8" ); ?></p>
	    <p><?php _e("Twitter App Secret: " ); ?><input type="text" name="vd_twitter_secret" value="<?php echo $twitter_secret; ?>" size="100"><?php _e("ex: 8Dz6DmAxE38uoojW99qSNgbae8mmZWayvSTK9y4v2n9jmyb8rP" ); ?></p>

	    <?php    echo "<h4>" . __( 'LinkedIn Settings', 'vd_trdom' ) . "</h4>"; ?>
        <p><?php _e("LinkedIn App Id: " ); ?><input type="text" name="vd_linkedin_app" value="<?php echo $linkedin_app; ?>" size="50"><?php _e("ex: " ); ?></p>
        <p><?php _e("LinkedIn App Secret: " ); ?><input type="text" name="vd_linkedin_secret" value="<?php echo $linkedin_secret; ?>" size="100"><?php _e("ex: " ); ?></p>


        <p class="submit">
        <input type="submit" name="Submit" value="<?php _e('Update Options', 'vd_trdom' ) ?>" />
        </p>
    </form>
</div>

