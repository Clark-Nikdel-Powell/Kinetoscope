<?php
/*
	Plugin Name: Kinetoscope
	Plugin URI: http://clarknikdelpowell.com
	Version: 0.1.0
	Description: Slide-show post type creator
	Author: Sam Mello
	Author URI: http://clarknikdelpowell.com/agency/people/sam/

	Copyright 2014+ Clark/Nikdel/Powell (email : sam@clarknikdelpowell.com)

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2 (or later),
	as published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


// Paths
DEFINE('KIN_PATH', 		plugin_dir_path(__FILE__));
DEFINE('KIN_URL',		plugin_dir_url(__FILE__));

// Databse options
DEFINE('KIN_TBLNAME', 	'kinetoscope');

// Post Type & Taxonomy names
DEFINE('KIN_SETNAME', 	'kinetoscope-settings');
DEFINE('KIN_DISPNAME', 	'Kinetoscope');
DEFINE('SLIDE_TAX',		'slide');
DEFINE('SLIDESHOW_TAX',	'slideshow');

// Option Keys
DEFINE('KIN_OPTION_DURATION',	'_kin_duration');
DEFINE('KIN_OPTION_TRANSITION',	'_kin_transition');
DEFINE('KIN_OPTION_FIELDS',		'_kin_fields');


// Functions
require_once(KIN_PATH.'functions/frontend.php');
require_once(KIN_PATH.'functions/backend.php');
require_once(KIN_PATH.'functions/settings.php');
require_once(KIN_PATH.'functions/wp-actions.php');

?>