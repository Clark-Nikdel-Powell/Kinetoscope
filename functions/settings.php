<?php

/**
 * Wordpress Plugin Settings
 * @package Kinetoscope
 * @since 0.1.0
 */

function kin_register_options() {
	global $kin_settings;
	global $localized;
	

	foreach ($kin_settings as $setting) {
		register_setting(KIN_SETNAME,$setting['key']);

		if ($setting['type']=='dynamic') {
			array_push($localized,$setting);
		}
	}
}


function kin_create_options() {
	add_options_page(KIN_DISPNAME, KIN_DISPNAME, 'manage_options', strtolower(KIN_DISPNAME), 'kin_options_page');
}


function kin_options_page() {

	global $localized;

	require_once(KIN_PATH.'html/settings-template.php');

	wp_enqueue_script('kin-dynamic',KIN_URL.'js/dynamic.js',null, true);

	if (count($localized)>0) 
		wp_localize_script('kin-dynamic','kinsettings',$localized);
}

?>