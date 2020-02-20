<?php

namespace Inc\Admin;


/**
 * Class AdminMenu
 * @package Inc\Admin
 */
class AdminMenu {


	/**
	 * AdminMenu constructor.
	 */
	public function __construct() {
		add_action('admin_menu', array($this,'add_database_menu'));
	}

	/**
	 * Action function for adding a admin menu
	 */
	public function add_database_menu(){
		add_options_page('Socializer settings', 'Socializer settings', 'manage_options', 'Socializer settings', array($this,'database_settings'),1);
	}

	/**
	 * Callback function for the admin menu page containing the form
	 */
	public function database_settings(){
		include(PLUGIN_PATH . '\inc\Assets\database_settings_template.php');
	}
}