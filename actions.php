<?php
add_action('init', 'kin_create_objects');
add_action('admin_menu', 'kin_create_sort_page');
add_action( 'wp_ajax_kinetoscope_save', 'kin_save_order' );
?>