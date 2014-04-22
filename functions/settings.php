<?php

/**
 * Wordpress Plugin Settings
 * @package Kinetoscope
 * @since 0.1.0
 */

function kin_register_options() {
	register_setting(KIN_SETNAME,'kin_duration');
	register_setting(KIN_SETNAME,'kin_transition');
	register_setting(KIN_SETNAME,'kin_fields');
}


function kin_create_options() {
	add_options_page(KIN_DISPNAME, KIN_DISPNAME, 'manage_options', strtolower(KIN_DISPNAME), 'kin_options_page');
}


function kin_options_page() {

	wp_enqueue_script('kin-dynamic',KIN_URL.'js/dynamic.js',null, true);
	wp_enqueue_style('kin-dynamic-style', KIN_URL.'css/dynamic.css');

	$custom_fields = kin_load_meta();
	$custom_fields = json_decode($custom_fields);
	
	if (!$custom_fields) $custom_fields = array();

	wp_localize_script('kin-dynamic', 'kin_fields', $custom_fields);

	require_once(KIN_PATH.'html/settings-template.php');
}


function kin_load_meta() {
	$options = get_option('kin_fields');
	if ($options && !is_wp_error($options))
		return $options;
	else
		return false;
}

?>