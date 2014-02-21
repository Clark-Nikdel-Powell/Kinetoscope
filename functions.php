<?php
function kin_create_objects() {

	$ptax = 'slide';
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


	$ttax = 'slideshow';
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


	add_action(
		'add_meta_boxes'
		,function($ptax) { 
			kin_create_meta($ptax);
		}
	);
	add_action('save_post', 'kin_save_meta');

	kin_create_tables();
}


function kin_create_tables() {
	global $wpdb;
	$table = $wpdb->prefix.KIN_TBLNAME;
	if ($wpdb->get_var("SHOW TABLES LIKE '".$table."'") != $table) {
		$sql = "
		CREATE TABLE ".$table." (
			meta_id int(11) NOT NULL AUTO_INCREMENT,
			term_id int(11),
			meta_key TEXT,
			meta_value TEXT,
			UNIQUE KEY id (meta_id)
		);";
		require_once(ABSPATH.'wp-admin/includes/upgrade.php');
		$tablecreated = dbDelta($sql);
	}
}


function kin_create_meta($posttype) {
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
		,'Position'
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

	$exists = $wpdb->get_row("SELECT meta_id FROM ".$pre.KIN_TBLNAME." WHERE term_id = '".$_POST['id']."' AND meta_key = 'order' LIMIT 1", ARRAY_A);
	if ($exists)
		$wpdb->query("UPDATE ".$pre.KIN_TBLNAME." SET meta_value = '".$_POST['data']."' WHERE meta_id = '".$exists['meta_id']."'");
	else
		$wpdb->query("INSERT INTO ".$pre.KIN_TBLNAME." (term_id, meta_key, meta_value) VALUES ('".$_POST['id']."','order','".$_POST['data']."')");

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


function get_slides($slideshow) {

	if ($slideshow && term_exists($slideshow->slug,'slideshow')) {
		$slides = get_posts(
			array(
				'post_type' => 'slide'
				,'posts_per_page' => -1
				,'slideshow' => $slideshow->slug
			)
		);

		$temp = $slides;
		
		global $wpdb;
		$pre = $wpdb->prefix;

		$request = $wpdb->get_row("SELECT meta_value FROM ".$pre.KIN_TBLNAME." WHERE term_id = '".$slideshow->term_id."' LIMIT 1",ARRAY_A);
		if ($request && !is_wp_error($request)) {
			if (isset($request['meta_value'])) {
				$positionsJSON = $request['meta_value'];
				$positions = json_decode($positionsJSON);

				$reordered = array();
				foreach ($slides as $key => $slide) {
					$position = array_search($slide->ID,$positions);
					if ($position !== false) {
						$reordered[$position] = $slide;
						unset($slides[$key]);
					}
				}
				foreach ($slides as $key => $slide) array_push($reordered,$slide);

				if ($reordered && is_array($reordered) && count($reordered)>0) {
					ksort($reordered);
					unset($slides);
					$slides = $reordered;
				}
				else $slides = $temp;
			}
		}
		return $slides;
	}
	else
		return 0;
}


function kin_print_order() {
	require_once(KIN_PATH.'html/sort-template.php');
	wp_enqueue_script('jquery-ui-sortable');
	wp_enqueue_script('kin-sortable',plugin_dir_url(__FILE__).'js/sortable.js');
}


function kin_create_sort_page() {
	add_submenu_page('edit.php?post_type=slide','Slideshow Sort Order','Sort Order','edit_pages','kin-sort-page',function() { kin_print_order(false); });
}
?>