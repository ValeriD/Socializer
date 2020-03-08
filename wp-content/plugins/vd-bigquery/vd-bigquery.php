<?php
/*
* Plugin Name: vd-bigquery
* Description: Plugin for integrating BigQuery
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

function activate(){
	flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'activate');

function deactivate(){
	flush_rewrite_rules();
}
register_deactivation_hook(__FILE__, 'deactivate');

require_once 'vendor/autoload.php';
require_once 'VDBigQuery.php';
if(class_exists('VDBigQuery')){
	VDBigQuery::registerDatasets();
}
if(class_exists('VDVisualization')){
	new VDVisualization();
}


