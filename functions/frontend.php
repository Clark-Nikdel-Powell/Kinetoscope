<?php

/**
 * Functions To Be Called From The Front End
 * @package Kinetoscope
 * @since 0.1.0
 */

function get_slideshow($slideshow) {

	if ($slideshow && term_exists($slideshow,'slideshow') && $term = get_term_by('slug',$slideshow,'slideshow')) {
		$slides = get_posts(
			array(
				'post_type' => 'slide'
				,'post_status' => 'publish'
				,'posts_per_page' => -1
				,'slideshow' => $slideshow
			)
		);

		$temp = $slides;
		
		global $wpdb;
		$pre = $wpdb->prefix;

		$stored = $wpdb->get_results("SELECT post_id,position FROM ".$pre.KIN_TBLNAME." WHERE term_id = '".$term->term_id."' ORDER BY position ASC",ARRAY_A);
		if ($stored && !is_wp_error($stored)) {
				
				$positions = array();
				foreach ($stored as $entry) {
					$positions[$entry['position']] = $entry['post_id'];
				}

				$reordered = array();
				foreach ($slides as $key => $slide) {

					$position = array_search($slide->ID,$positions);
					if ($position !== false) {
						$reordered[$position] = $slide;
						unset($slides[$key]);
					}
				}

				if ($reordered && is_array($reordered) && count($reordered)>0) {
					ksort($reordered);
					$reordered = array_merge($reordered,$slides);
					unset($slides);
					$slides = $reordered;
				}
				else $slides = $temp;
			
		}

		$metas = get_option('kin_fields');
		foreach ($slides as $key => $slide) {

			$fiid = get_post_meta($slide->ID, '_thumbnail_id', true);
			if ($fiid) {
				$url = wp_get_attachment_image_src($fiid, 'medium');
				if (isset($url[0]) && $url[0]) {
					$slide->photo = $url[0];
					$slides[$key]->photo = $url[0];
				}
			}
			
			if ($metas) {
				$metas = json_decode($metas);
				if (count($metas)>0) {
					$slides[$key]->meta = array();
					foreach ($metas as $metakey=>$meta) {
						$val = get_post_meta($slide->ID, $metakey, true);
						if ($val) {
							$slides[$key]->meta[$metakey] = $val;
						}
					}
				}
			}
		}

		return $slides;
	}
	else
		return 0;
}
?>