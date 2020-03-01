<?php
include_once 'wp-load.php';

get_header();
?>

	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">

<?php
	if ( have_posts() ) {

		while ( have_posts() ) {
			 the_post();
			 if(get_the_author_meta('ID') === get_current_user_id()){ ?>
	            <h3><?php the_title(); ?></h3>
				<p><?php the_content();?></p>
				<?php if(get_post_meta(get_the_ID(), 'post_img', true)) { ?>
					 <img src="<?php echo get_post_meta( get_the_ID(), 'post_img', true ); ?>"
					      style="height:500px; width:600px; position: center ">
					 <p><?php echo get_post_meta(get_the_ID(), 'post_likes', true)?>; <?php echo get_post_meta(get_the_ID(), 'post_comments', true)?></p>
					 <?php
				 }
			}else{
			 	die('First you should login');
			 }
		}
	}
	else{
		echo 'No posts';
	}?>

		</div>
	</div>
<?php

get_footer();
