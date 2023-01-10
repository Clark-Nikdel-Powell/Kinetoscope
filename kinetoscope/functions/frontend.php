<?php

/**
 * Functions To Be Called From The Front End
 * @package Kinetoscope
 * @since 0.1.0
 */

function get_slideshow($showname) {

	// only continue if slideshow name is providerd
	if ($showname && term_exists($showname, SLIDESHOW_TAX) && $term = get_term_by('slug', $showname, SLIDESHOW_TAX)) {

		// set our master slideshow array
		$slideshow = array();

		// set other slideshow related meta
		$slideshow['duration'] 	= get_option(KIN_OPTION_DURATION);
		$slideshow['speed'] 	= get_option(KIN_OPTION_TRANSITION);

		// get all related slides
		$slides = get_posts(
			array(
				'post_type' => SLIDE_TAX
				,'post_status' => 'publish'
				,'posts_per_page' => -1
				,'slideshow' => $showname
			)
		);

		// set temp for error fallback
		$temp = $slides;
		
		// get global DB var and prefix
		global $wpdb;
		$pre = $wpdb->prefix;

		// get position for this slideshow from custom DB
		$stored = $wpdb->get_results("SELECT post_id,position FROM ".$pre.KIN_TBLNAME." WHERE term_id = '".$term->term_id."' ORDER BY position ASC",ARRAY_A);
		if ($stored && !is_wp_error($stored)) {
				
			// get position list
			$positions = array();
			foreach ($stored as $entry) {
				$positions[$entry['position']] = $entry['post_id'];
			}

			// loop through slides and get new positions, also remove from first array
			$reordered = array();
			foreach ($slides as $key => $slide) {
				
				$position = array_search($slide->ID,$positions);
				if ($position !== false) {
					$reordered[$position] = $slide;
					unset($slides[$key]);
				}
			}

			//  sort new array by position and then merge left-over slides that didn't have positions
			if ($reordered && is_array($reordered) && count($reordered)>0) {
				ksort($reordered);
				$reordered = array_merge($reordered,$slides);
				unset($slides);
				$slides = $reordered;
			}
			// if we didn't get any positions, just return our default
			else $slides = $temp;
		}

		// get META fields
		$metas = get_option(KIN_OPTION_FIELDS);
		if ($metas) $metas = json_decode($metas);

		// loop slides again
		foreach ($slides as $key => $slide) {

			// get featured image id
			$fiid = get_post_meta($slide->ID, '_thumbnail_id',true);

			// if it's set, load up data
			if ($fiid) {

				// get meta data for photo
				$data = wp_get_attachment_metadata($fiid,true);

				// get url for full size image
				$url = wp_get_attachment_url($fiid);

				// if our data is set, proceed
				if (isset($data) && $data) {

					// if the url is set, at it to the data array
					if ($url) $data['url'] = $url;

					// set the data in the slide array
					$slides[$key]->featured_image = $data;
				}
			}
			
			// get all meta fields for each slide
			if (is_object($metas) && !empty($metas)) {

				// set array
				$slides[$key]->meta = array();

				// for each meta declared by system
				foreach ($metas as $metakey=>$meta) {

					// get the data for this slide and meta
					$val = get_post_meta($slide->ID, $metakey, true);
					// procceed if some data exists
					if ($val) {

						// set the name (for display purposes) of this meta
						$slides[$key]->meta[$metakey]['name'] = $meta->name;

						// if this is an image, get it's meta data and url
						if ($meta->type=='image') {

							// get meta data
							$data = wp_get_attachment_metadata($val,true);

							// get url
							$url = wp_get_attachment_url($val);

							// if data is set, proceed
							if (isset($data) && $data) {

								// if url is set, add it to the data array
								if ($url) $data['url'] = $url;

								// set the value as the image meta data
								$slides[$key]->meta[$metakey]['value'] = $data;
							}
						}
						// otherwise just set the value
						else
							$slides[$key]->meta[$metakey]['value'] = $val;
					}
				}
			}
		}

		// set master array with slides
		$slideshow['slides'] 	= $slides;

		// return slideshow
		return $slideshow;
	}
	// error by returning false
	else
		return false;
}

?>