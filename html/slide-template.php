	<p>
	<h3 id="<?php echo $show->slug ?>_slug"><?php echo $show->name ?></h3>
	<ul class="kin-slideshow" id="<?php echo $show->slug ?>" data-id="<?php echo $show->term_id ?>">
		<?php

		$slides = get_slides($show);
		if ($slides && count($slides)>0) {

			foreach ($slides as $key => $slide) {

				$photo = '';
				$fiid = get_post_meta($slide->ID, '_thumbnail_id', true);
				if ($fiid) {
					$url = wp_get_attachment_image_src($fiid, 'medium');
					if (isset($url[0]) && $url[0]) $photo = $url[0];
				}

			?>
		<li class="kin-slides" id="<?php echo $slide->post_name ?>" data-id="<?php echo $slide->ID ?>" style="background-image:url('<?php echo $photo ?>');"><pos><?php echo ($key+1) ?>.</pos><?php echo $slide->post_title ?></li>
			<?php
			}
		}
		else {
		?>
		No slides have been added to this slideshow.
		<?php
		}
		?>
	</ul>
	</p>