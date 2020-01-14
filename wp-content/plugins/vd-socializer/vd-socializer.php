<?php
/*
 * Plugin Name: vd-socializer
 * Description: Plugin for adding Social Networks accounts
 * Author: Valeri Dobrev
 * License: GPLv2 or late
 */
/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

Copyright 2005-2015 Automattic, Inc.
*/

//Checks for proper activation
if( !function_exists( 'add_action' ) ){
	die;
}

//Defining constants
define( 'PLUGIN_PATH', plugin_dir_path(__FILE__) );
define( 'PLUGIN_URL', plugin_dir_url(__FILE__) );


//Require once the Composer Autoloader
if( file_exists(__FILE__) . './vendor/autoload.php' ){
	require_once dirname(__FILE__) . './vendor/autoload.php';
}


/**
 * The code that runs when the plugin is activated
 */
function activate_vd_plugin(){
	Inc\Base\Activate::activate();
}
register_activation_hook( __FILE__, 'activate_vd_plugin' );


/**
 * The code that runs when the plugin is deactivated
 */
function deactivate_vd_plugin(){
	Inc\Base\Deactivate::deactivate();
}
register_deactivation_hook( __FILE__, 'deactivate_vd_plugin' );



//Initialize the core classes of the plugin
if(class_exists('Inc\\Init')){
	Inc\Init::register_services();
}

