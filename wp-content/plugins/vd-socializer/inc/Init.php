<?php
/**
 * @package vd-socializer
 */

namespace Inc;


use Inc\Admin\AdminMenu;
use Inc\Base\Session;
use Inc\FacebookConf\FacebookAuth;
use Inc\LinkedIn\LinkedInAuth;
use Inc\Twitter\TwitterAuth;

final class Init{

	/**
	 * Store all classes in array
	 * @return array full list of classes
	 */
	public static function get_services(){
		return [
			Session::class,
			FacebookAuth::class,
			TwitterAuth::class,
			LinkedInAuth::class,
			AdminMenu::class
		];
	}

	/**
	 * Loop through the classes,
	 * for each creates an instance
	 */
	public static function register_services(){
		foreach ( self::get_services() as $class ){
			 $service = Init::instantiate($class);
			 if(method_exists($service, 'register')){
			 	$service->register();
			 }

		}
	}

	public static function instantiate($class){
		return new $class;
	}


}