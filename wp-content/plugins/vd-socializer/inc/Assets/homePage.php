<?php
include_once 'wp-load.php';

get_header();
?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">

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
					 <?php
				 }
			}else{
			 	die('First you should login');
			 }
		}
	}?>

			</main>
	</div>
<?php

get_footer();
