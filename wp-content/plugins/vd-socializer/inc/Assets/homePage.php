<?php
include_once 'wp-load.php';

get_header();
?>
	<style>
		.socializer-post{
			display: inline-block;
			float: left;
			width: 40%;
			margin-left: 5%;
			margin-right: 5%;
            margin-bottom: 5%;
			text-align: center;
			border-bottom: black 1px solid;
		}
	</style>
	<div id="primary" class="content-area">



<?php
	if ( have_posts() ) {

		while ( have_posts() ) { ?>
			<div class = "socializer-post">
			 <?php the_post();

			 if(get_the_author_meta('ID') === get_current_user_id()){ ?>

				 <?php if(get_post_meta(get_the_ID(), 'post_img', true)) { ?>
                     <img src="<?php echo get_post_meta( get_the_ID(), 'post_img', true ); ?>" style="display: block; margin-left: auto; margin-right: auto;width: 600px; height: 400px">
				<?php
				 }?>
                 <p><?php the_content();?></p>
                 <p>Likes: <?php echo get_post_meta(get_the_ID(), 'post_likes', true); ?> Shares: <?php echo get_post_meta(get_the_ID(), 'post_shares', true);?></p>





				 <?php
			}else{
			 	die('First you should login');
			 }
			 ?>
			</div> <?php
		}
	}
	else{
		echo 'No posts';
	}?>

	</div>
<?php

get_footer();
