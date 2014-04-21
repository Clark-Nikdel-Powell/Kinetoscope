<?php

/**
 * Functions Used For the Plugin (backend)
 * @package Kinetoscope
 * @since 0.1.0
 */

function kin_create_objects() {

	$ptax = SLIDE_TAX;
	$psing = ucwords($ptax);
	$pplur = $psing.'s';

	$labels = array(
		'name' => $pplur
		,'singular_name' => $psing
		,'add_new_item' => 'Add New '.$psing
		,'edit_item' => 'Edit '.$psing
		,'new_item' => 'New '.$psing
		,'view_item' => 'View '.$psing
		,'search_items' => 'Search '.$pplur
		,'not_found' => 'No '.$pplur.' Found'
		,'not_found_in_trash' => 'No '.$pplur.' Found In Trash'
	);
	$args = array(
		'labels' => $labels
		,'public' => true
		,'has_archive' => false
		,'exclude_from_search' => true
		,'menu_position'
		,'menu_icon' => 'dashicons-images-alt2'
		,'supports' => array(
			'title'
			,'editor'
			,'thumbnail'
			,'comments' => false
		)
	);
	register_post_type($ptax,$args);


	$ttax = SLIDESHOW_TAX;
	$tsing = ucwords($ttax);
	$tplur = $tsing.'s';

	$labels = array(
		'name' => $tplur
		,'singular_name' => $tsing
		,'all_items' => 'All '.$tplur
		,'edit_item' => 'Edit '.$tsing
		,'view_item' => 'View '.$tsing
		,'update_item' => 'Update '.$tsing
		,'add_new_item' => 'Add New '.$tsing
		,'search_items' => 'Search '.$tplur
		,'popular_items' => 'Popular '.$tplur
		,'add_or_remove_items' => 'Add or remove '.$tplur
		,'choose_from_most_used' => 'Choose from most used '.$tplur
		,'not_found' => 'No '.$tplur.' Found'
	);
	$args = array(
		'labels' => $labels
		,'hierarchical' => true
		,'show_admin_column' => true
	);
	register_taxonomy($ttax,$ptax,$args);
}


function kin_create_tables() {
	global $wpdb;
	$table = $wpdb->prefix.KIN_TBLNAME;
	if ($wpdb->get_var("SHOW TABLES LIKE '".$table."'") != $table) {
		$sql = "
		CREATE TABLE ".$table." (
			meta_id int(11) NOT NULL AUTO_INCREMENT,
			term_id int(11),
			post_id int(11),
			position int(11),
			UNIQUE KEY id (meta_id)
		);";
		require_once(ABSPATH.'wp-admin/includes/upgrade.php');
		$tablecreated = dbDelta($sql);
	}
}


function kin_create_meta() {

	$posttype = SLIDE_TAX;

	$key = 'link';
	add_meta_box(
		'kin_'.$key
		,ucwords($key)
		,'kin_print_meta'
		,$posttype
		,'normal'
		,'core'
	);

	add_meta_box(
		'kin_position'
		,'Slideshow Positions'
		,'kin_print_order'
		,$posttype
		,'normal'
		,'core'
	);
}



function kin_print_meta($post) {
	$val = get_post_meta($post->ID,'kin_link',true);
	wp_nonce_field('kin_print_meta', 'kin_nonce');
	echo '<input type="text" class="widefat" name="kin_link" value="'.$val.'" />';
}



function kin_save_order() {
	
	global $wpdb;
	$pre = $wpdb->prefix;
	
	echo 'working';

	if ($_POST 
		&& isset($_POST['id']) 
		&& isset($_POST['data']) 
		&& $slides = json_decode($_POST['data'])
	) {
		$wpdb->query("DELETE FROM ".$pre.KIN_TBLNAME." WHERE term_id = '".$_POST['id']."'");
		foreach ($slides as $key=>$slide) {
		
			$wpdb->query("
				INSERT INTO ".$pre.KIN_TBLNAME." (
					term_id
					,post_id
					,position
				) VALUES (
					'".$_POST['id']."'
					,'".$slide."'
					,'".$key."'
				)
			");
		}
	}

	die();
}


function kin_remove_slide() {
	if ($_POST 
		&& isset($_POST['id'])
		&& isset($_POST['term_id'])
	) {

		$terms = wp_get_post_terms($_POST['id'], 'slideshow');
		$keep = array();
		foreach ($terms as $term) {
			echo $term->term_id.',';
			if ($term->term_id != $_POST['term_id']) {
				array_push($keep,$term->term_id);
			}
		}
		
		if ($keep && count($keep)>0) wp_set_post_terms($_POST['id'], $keep, 'slideshow');
		else wp_delete_object_term_relationships($_POST['id'],'slideshow');
	}
	die();
}



function kin_save_meta($post_id) {
	if(!isset( $_POST['kin_nonce']))
		return $post_id;

	$nonce = $_POST['kin_nonce'];

	if (!wp_verify_nonce($nonce,'kin_print_meta'))
		return $post_id;

	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
		return $post_id;

	if (!current_user_can('edit_post',$post_id))
		return $post_id;

	$data = sanitize_text_field($_POST['kin_link']);
	update_post_meta($post_id,'kin_link',$data);
}


function kin_delete_post_order($pid) {
	if ($pid) {
		global $wpdb;
		$pre = $wpdb->prefix;
		$wpdb->query("DELETE FROM ".$pre.KIN_TBLNAME." WHERE post_id = '".$pid."'");
	}
}


function kin_print_order() {
	require_once(KIN_PATH.'html/sort-template.php');

	wp_enqueue_script('jquery-ui-sortable');
	wp_enqueue_script('jquery-ui-draggable');
	wp_enqueue_script('jquery-ui-droppable');

	wp_enqueue_script('kin-sortable',KIN_URL.'js/sortable.js',null,true);
	wp_enqueue_style('kin-sortable-style',KIN_URL.'css/sortable.css');
}


function kin_create_sort_page() {
	add_submenu_page('edit.php?post_type=slide','Slideshow Sort Order','Sort Order','edit_pages','kin-sort-page',function() { kin_print_order(false); });
}

?>