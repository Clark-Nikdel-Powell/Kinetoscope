<?php

function kin_form_output($setting) {
	if ($setting['type']=='text') {
		?>
		<input type="text" name="<?= $setting['key'] ?>" id="<?= $setting['key'] ?>" value="<?php form_option($setting['key']); ?>" class="regular-text" />
		<p class="description"><?= $setting['description'] ?></p>
		<?php
	}
	elseif ($setting['type']=='number') {
		?>
		<input type="number" step="1" min="0" name="<?= $setting['key'] ?>" id="<?= $setting['key'] ?>" value="<?php form_option($setting['key']); ?>" class="small-text" />
		<?= $setting['description'] ?>
		<?php
	}
	elseif ($setting['type']=='dynamic') {

		$options = form_option($setting['key']);
		if ($options && is_array($options) && count($options)>0) {
			foreach ($options as $option) {

				if ($option['type']=='dynamic')
					echo 'Cannot use dynamic types in a dynamic type.';
				else
					kin_form_output($option);
			}
		}
		?>
		<div class="dashicons dashicons-plus-alt kindynamic" id="add_<?= $setting['key'] ?>"></div>
		<?php
	}
	else
		echo 'unknown type';
}

?>

<div class="wrap">
	<h2><?= KIN_DISPNAME ?></h2>
	<br/>
 
	<form method="post" action="options.php">
		<?php settings_fields(KIN_SETNAME); ?>
		<?php do_settings_sections(KIN_SETNAME); ?>
		<table class="form-table" style="margin-bottom:20px;">
		<?php

		global $kin_settings;
		foreach ($kin_settings as $setting) {
			?>

			<tr valign="top">
				<th scope="row">
				<label for="<?= $setting['key'] ?>"><?= $setting['name'] ?></label>
				</th>
				<td>
				<?= kin_form_output($setting); ?>
				</td>
			</tr>
			
		<?php

		}

		?>
		</table>
		<?php  submit_button(null,'primary',null,false); ?>
		</form>

</div>