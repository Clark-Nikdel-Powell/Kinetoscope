<?php

global $post;
$editslide = false;

if (isset($post) && $post) {
	$editslide = true;
	$shows = wp_get_post_terms($post->ID,'slideshow');
}
else $shows = get_terms('slideshow');
?>
<style>
.kin-container {
	padding-left:20px;
}

.kin-slides {
	cursor:pointer;
	float:left;
	display:inline-block;
	margin:5px;
	border:3px solid #000;
	border-radius:10px;
	padding:3px;
	background:#fff;
	width:150px;
	height:170px;
	background-position:top 50px left 50%;
	background-repeat:no-repeat;
	text-align:center;
	padding-top:5px;
	font-weight:bold;
	font-size:8pt;
}

.kin-slides-small {
	width:100px;
	height:100px;
}

.kin-remove {
	position:relative;
	width:10px;
	height:10px;
	top:0px;
	left:0px;
	font-size:20pt;
	font-weight:bold;
}

.kin-trash:after {
	font-family: "dashicons";
	content: "\f182";
	color:#CCC;
	font-size:5em;
}

.kin-hover {
	border:2px solid #000;
}

.kin-placeholder {
	border:3px dashed #DEDEDE;
	background-color:#FFF;
}

pos {
	padding-right:5px;
}

.kin-updated {
	color:#090;
	font-style:italic;
}

p {
	padding-top:5px;
	clear:both;
}
</style>
<?php
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