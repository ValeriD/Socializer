<?php
/**
 * @package vd-socializer
 */

namespace Inc\Base;


class Deactivate{
	public static function deactivate() {
		flush_rewrite_rules();

		$postId = post_exists( 'Accounts' );
		if ( $postId ) {
			wp_delete_post($postId);
		}
	}
}