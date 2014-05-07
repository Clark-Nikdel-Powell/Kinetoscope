<?php

global $post;
$editslide = false;

if (isset($post) && $post) {
	$editslide = true;
	$shows = wp_get_post_terms($post->ID, SLIDESHOW_TAX);
}
else $shows = get_terms(SLIDESHOW_TAX);


if ($editslide !== true) {
	?>
<h2><?= ucwords(SLIDESHOW_TAX) ?> Sort Order</h2>
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
		This <?= strtolower(SLIDE_TAX) ?> has not been added to any <?= strtolower(SLIDESHOW_TAX) ?>.
		<?php
	}
	else {
		?>
	No <?= strtolower(SLIDESHOW_TAX) ?> have been created yet.
		<?php
	}
}
?>
</div>