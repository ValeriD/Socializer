<?php
/**
 * @package vd-socializer
 */

namespace Inc\Base;


class Activate{
	public static function activate(){
		self::initialConfiguration();
		flush_rewrite_rules();
	}

	public static function initialConfiguration(){
		wp_insert_post(self::pageArray());
	}
	public static function pageArray(){
		return array(
			'post_type' => 'page',
			'post_title' => 'Accounts',
			'post_name' => 'accounts',
			'post_content' => '[twitter]',
			'post_status' => 'publish',
			'guid' => 'https://socializer.com/accounts',
			'post_author' => get_current_user_id()

		);
	}
}