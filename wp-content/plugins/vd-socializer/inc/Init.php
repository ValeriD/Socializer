<?php
/**
 * @package vd-socializer
 */

namespace Inc;


final class Init{

	/**
	 * Store all classes in array
	 * @return array full list of classes
	 */
	public static function get_services(){
		return [

		];
	}

	/**
	 * Loop through the classes,
	 * for each creates an instance
	 * and call the register method
	 */
	public static function register_services(){
		foreach ( self::get_services() as $class ){
			$service = self::instantiate( $class );
			if( method_exists($service, 'register') ){
				$service->register();
			}
		}
	}

	/**
	 * Initializing a class
	 * @param $class class from the services array
	 * @return new class instance
	 */
	private static function instantiate( $class ){
		return new $class;
	}
}