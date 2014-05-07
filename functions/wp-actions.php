<?php

/**
 * Wordpress Plugin Actions
 * @package Kinetoscope
 * @since 0.1.0
 */

add_action('init', 							'kin_create_objects');
add_action('init', 							'kin_create_tables');
add_action('add_meta_boxes',				'kin_create_meta');
add_action('save_post',						'kin_save_meta');

add_action('admin_menu', 					'kin_create_sort_page');
add_action('wp_ajax_kinetoscope_save', 		'kin_save_order' );
add_action('wp_ajax_kinetoscope_remove', 	'kin_remove_slide' );
add_action('delete_post', 					'kin_delete_post_order');

add_action('admin_init',					'kin_register_options');
add_action('admin_menu',					'kin_create_options');

add_theme_support('post-thumbnails');
?>