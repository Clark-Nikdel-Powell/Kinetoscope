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

	$type_labels = array(
		'name' => $pplur
	,   'singular_name' => $psing
	,   'add_new_item' => 'Add New '.$psing
	,   'edit_item' => 'Edit '.$psing
	,   'new_item' => 'New '.$psing
	,   'view_item' => 'View '.$psing
	,   'search_items' => 'Search '.$pplur
	,   'not_found' => 'No '.$pplur.' Found'
	,   'not_found_in_trash' => 'No '.$pplur.' Found In Trash'
	);
	$type_args = array(
		'labels' => $type_labels
	,   'public' => true
	,   'has_archive' => false
	,   'exclude_from_search' => true
	,	'publicly_queryable' => false
	,   'menu_position'
	,   'menu_icon' => 'dashicons-images-alt2'
	,   'supports' => array(
			'title'
			,'editor'
			,'thumbnail'
			,'comments' => false
		)
	);
	register_post_type($ptax,$type_args);


	$ttax = SLIDESHOW_TAX;
	$tsing = ucwords($ttax);
	$tplur = $tsing.'s';

	$tax_labels = array(
		'name' => $tplur
	,   'singular_name' => $tsing
	,   'all_items' => 'All '.$tplur
	,   'edit_item' => 'Edit '.$tsing
	,   'view_item' => 'View '.$tsing
	,   'update_item' => 'Update '.$tsing
	,   'add_new_item' => 'Add New '.$tsing
	,   'search_items' => 'Search '.$tplur
	,   'popular_items' => 'Popular '.$tplur
	,   'add_or_remove_items' => 'Add or remove '.$tplur
	,   'choose_from_most_used' => 'Choose from most used '.$tplur
	,   'not_found' => 'No '.$tplur.' Found'
	);
	$tax_args = array(
		'labels' => $tax_labels
	,   'hierarchical' => true
	,   'show_admin_column' => true
	);
	register_taxonomy($ttax,$ptax,$tax_args);
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
	$metas = get_option(KIN_OPTION_FIELDS);
	if ($metas) {
		$metas = json_decode($metas);
		if (count($metas)>0) {
			$key = 'meta';
			add_meta_box(
				'kin_'.$key
			,   ucwords(SLIDE_TAX.' '.$key)
			,   'kin_print_meta'
			,   $posttype
			,   'normal'
			,   'core'
			);
		}
	}

	// add_meta_box(
	// 	'kin_position'
	// ,   ucwords(SLIDESHOW_TAX).' Positions'
	// ,   'kin_print_order'
	// ,   $posttype
	// ,   'normal'
	// ,   'core'
	// );
}



function kin_print_meta($post) {

	wp_enqueue_script('kin-dynamic',KIN_URL.'js/edit.js',null, true);

	wp_nonce_field('kin_print_meta', 'kin_nonce');

	$metas = get_option(KIN_OPTION_FIELDS);
	if ($metas) {
		$metas = json_decode($metas);
		if (count($metas)>0) {
			foreach ($metas as $key=>$meta) {
				echo '<p>';
				echo '<strong>'. ucfirst($meta->name) .'</strong>';

				if ($meta->type == 'textarea')
					echo '<textarea class="widefat" name="'.$key.'">'.get_post_meta($post->ID,$key,true).'</textarea>';
				elseif ($meta->type == 'image') {

					$image = wp_get_attachment_image(get_post_meta($post->ID,$key,true), 'thumbnail');

					echo '<br/>';
					echo '<input type="hidden" name="'.$key.'" />';
					echo '<a href="#" class="button kin_add_media" data-editor="'.esc_attr($key).'">Select Image</a>';
					echo '<a href="#" class="kin_add_media" data-editor="'.esc_attr($key).'"><div id="'.$key.'">'.$image.'</div></a>';
				}
				else
					echo '<input type="text" class="widefat" name="'.$key.'" value="'.get_post_meta($post->ID,$key,true).'" />';

				echo '</p>';
			}
		}
	}
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
				,   post_id
				,   position
				) VALUES (
					'".$_POST['id']."'
				,   '".$slide."'
				,   '".$key."'
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

	$metas = get_option(KIN_OPTION_FIELDS);
	if ($metas) {
		$metas = json_decode($metas);
		if (count($metas)>0) {
			foreach ($metas as $key=>$meta) {
				if (isset($_POST[$key])) {
					$data = sanitize_text_field($_POST[$key]);
					update_post_meta($post_id,$key,$data);
				}
			}
		}
	}


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
	add_submenu_page('edit.php?post_type='.SLIDE_TAX,'Slideshow Sort Order','Sort Order','edit_pages','kin-sort-page',function() { kin_print_order(false); });
}

?>