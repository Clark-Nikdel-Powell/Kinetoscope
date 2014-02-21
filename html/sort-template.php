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
	width:100px;
	height:100px;
	background-position:top 50px left 50%;
	background-repeat:no-repeat;
	text-align:center;
	padding-top:5px;
	font-weight:bold;
	font-size:8pt;
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
<h2>Slideshow Sort Order</h2>
<div class="kin-container">
<?php
global $post;
if (isset($post) && $post) $shows = wp_get_post_terms($post->ID,'slideshow');
else $shows = get_terms('slideshow');

if ($shows && count($shows)>0 && !is_wp_error($shows)) {

	foreach ($shows as $show) {
		include(KIN_PATH.'html/slide-template.php');
	}
}
else {
	?>
	No slideshows have been created yet.
	<?php
}
?>
</div>