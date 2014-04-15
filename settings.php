<?php

/**
 * Wordpress Plugin Settings
 * @package Kinetoscope
 * @since 0.1.0
 */

function kin_register_options() {
	register_setting(KIN_SETNAME,'kin_duration');
	register_setting(KIN_SETNAME,'kin_transspeed');
	register_setting(KIN_SETNAME,'kin_fields');
}


function kin_create_options() {
  add_options_page(KIN_DISPNAME, KIN_DISPNAME, 'manage_options', strtolower(KIN_DISPNAME), 'kin_options_page');
}


function kin_options_page() {
?>
<div class="wrap">
  <h2><?= KIN_DISPNAME ?></h2>
  <br/>
  <?php /*
  <form method="post" action="options.php">
    <?php settings_fields(KIN_SETNAME); ?>
    <?php do_settings_sections(KIN_SETNAME); ?>
    <table class="form-table" style="margin-bottom:20px;">
      <tr valign="top">
        <th scope="row">
        <label for="libsyn_xml_url">Libsyn RSS URL</label>
        </th>
        <td>
          <input name="libsyn_xml_url" type="text" id="libsyn_xml_url" value="<?php form_option('kin_duration'); ?>" class="regular-text" />
          <p class="description">Example: http://yourdomain.com/rss</p>
        </td>
      </tr>
      <tr valign="top">
        <th scope="row">
        <label for="libsyn_min_date">Cut-off Date</label>
        </th>
        <td>
          <input type="text" id="libsyn_min_date" name="libsyn_min_date" value="<?php form_option('kin_duration'); ?>" class="regular-text" />
          <p class="description">Posts older than date will not be imported.</p>
        </td>
      </tr>
    </table>
    <?php  submit_button(null,'primary',null,false); ?>
    </form>
    */ ?>
</div>
<?php
}
?>