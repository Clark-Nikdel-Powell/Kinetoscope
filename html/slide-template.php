<h3 id="<?php echo $show->slug ?>_slug"><?php echo $show->name ?></h3>
<ul class="kin-slideshow" id="<?php echo $show->slug ?>" data-id="<?php echo $show->term_id ?>">
	<?php

	$slideshow = get_slideshow($show->slug);
	if ($slideshow['slides'] && count($slideshow['slides'])>0) {

		foreach ($slideshow['slides'] as $key => $slide) {
		?>
	<li class="kin-slides<?php if($editslide == true) echo ' kin-slides-small'; ?>" id="<?php echo $slide->post_name ?>" data-id="<?php echo $slide->ID ?>">
		<?php
		if (isset($slide->featured_image) && $slide->featured_image) {
			echo '<span class="image" style="border:none; background-image:url(\''.$slide->featured_image['url'].'\');"></span>';
		} else {
			echo '<span class="image blank"></span>';
		}
		?>
		<span class="title"><?php echo $slide->post_title ?></span>
	</li>
		<?php
		}
	}
	else {
		?>
	No <?= strtolower(SLIDE_TAX) ?>(s) have been added to this <?= strtolower(SLIDESHOW_TAX) ?>.
		<?php
	}
	?>
<?php
if ($slideshow['slides'] && count($slideshow['slides'])>0) {
	?>
	<li class="kin-slides kin-placeholder kin-trash<?php if($editslide == true) echo ' kin-slides-small'; ?>"></li>
<?php
} ?>
</ul>
<?
