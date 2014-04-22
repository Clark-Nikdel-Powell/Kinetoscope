
<div class="wrap">
	<h2><?= KIN_DISPNAME ?></h2>
	<br/>
 
	<form method="post" action="options.php">
		<?php settings_fields(KIN_SETNAME); ?>
		<?php do_settings_sections(KIN_SETNAME); ?>
		<table class="form-table" style="margin-bottom:20px;">
		<tr valign="top">
			<th scope="row">
			<label for="kin_duration">Slide Duration</label>
			</th>
			<td>
				<input type="number" name="kin_duration" id="kin_duration" class="small-text" value="<?php form_option('kin_duration') ?>"> (seconds)
			</td>
		</tr>
		<tr valign="top">
			<th scope="row">
			<label for="kin_transition">Transition Speed</label>
			</th>
			<td>
				<input type="number" name="kin_transition" id="kin_transition" class="small-text" value="<?php form_option('kin_transition') ?>"> (seconds)
			</td>
		</tr>
		<tr valign="top">
			<th scope="row">
			<label for="kin_meta">Additional Slide Meta</label>
			</th>
			<td>
				<input type="hidden" name="kin_fields" id="kin_fields" value="<?= form_option('kin_fields') ?>" />
				<table id="kin_meta_table" style="padding:none;border-spacing:0px;">
				<tr>
					<td style="padding:0px;"><input type="text" name="kin_meta_name_add" maxlength="30" id="kin_meta_name_add" /></td>
					<td style="padding:0px;">
						<select name="kin_meta_type_add" id="kin_meta_type_add">
						<option value="text">text</option>
						<option value="textarea">textarea</option>
						<option value="image">image</option>
						</select>
					</td>
					<td style="padding:0px;">
						<div class="dashicons dashicons-plus-alt kin_add"></div>
					</td>
				</tr>
				</table>
			</td>
		</tr>
		</table>
		<?php  submit_button(null,'primary',null,false); ?>
		</form>

</div>