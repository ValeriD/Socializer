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
		//wp_insert_post(self::pageArray());
	}
	public static function pageArray(){
		return array(

		);
	}
}