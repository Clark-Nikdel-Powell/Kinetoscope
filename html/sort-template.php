<?php

global $post;
$editslide = false;

if (isset($post) && $post) {
	$editslide = true;
	$shows = wp_get_post_terms($post->ID,'slideshow');
}
else $shows = get_terms('slideshow');


if ($editslide !== true) {
	?>
<h2>Slideshow Sort Order</h2>
	<?php
}
?>
<div class="kin-container">
<?php


if ($shows && count($shows)>0 && !is_wp_error($shows)) {

	foreach ($shows as $show) {
		include(KIN_PATH.'html/slide-template.php');
	}
}
else {

	if ($editslide == true) {
		?>
		This slide has not been added to any slideshows.
		<?php
	}
	else {
		?>
	No slideshows have been created yet.
		<?php
	}
}
?>
</div>