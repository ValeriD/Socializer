<?php
/*
* Plugin Name: vd-login
* Description: Plugin for login and registration
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

if(!defined('ABSPATH')){
	die("Silence is golden");
}

//Defining constants
define( 'VD_LI_PLUGIN_PATH', plugin_dir_path(__FILE__) );
define( 'VD_LI_PLUGIN_URL', plugin_dir_url(__FILE__) );

include_once VD_LI_PLUGIN_PATH . 'Base\Activator.php';

function vd_activate_plugin(){
	Activator::activate();
}
register_activation_hook(__FILE__, 'vd_activate_plugin');

include_once VD_LI_PLUGIN_PATH . 'Base/Deactivator.php';
function vd_deactivate_plugin(){
	Deactivator::deactivate();
}
register_activation_hook(__FILE__, 'vd_deactivate_plugin');

if(class_exists(Init::class)){
	Init::register_services();
}