<?php


final class Init {
	public static function get_services() {
		return [

		];
	}

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