<?php


namespace Inc\Base;


class Session {


	/**
	 * Session constructor.
	 */
	public function __construct() {
		session_start();
	}
}