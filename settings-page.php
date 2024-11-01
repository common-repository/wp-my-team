<?php  

/*
Author: Selvabalaji
Version: 1.0
Author URI:http://www.tbsin.com/
Packages : Setting page
*/
  
//admin enque scrips
function myteam_enqueue_settings_js() {
	wp_enqueue_script( 'jquery-ui-tabs' );
	wp_deregister_style( 'myteam-settings-style' );
	wp_register_style( 'myteam-settings-style', plugins_url( 'css/settings.css', __FILE__ ),array(),false,false);
	wp_enqueue_style( 'myteam-settings-style' );		
}
  
//options page build 
function myteam_settings_page () { 
	global $myteam_labels;
	//myteam_enqueue_settings_js();
?>

 <div class="wrap">
<h2>Settings</h2>
    <?php 
	if(isset($_GET['settings-updated']) && $_GET['settings-updated']=="true") { 
		$msg = "Settings Updated";
		myteam_message($msg);
    } ?>
	<form method="post" action="options.php" id="dsform">
    <?php 
	settings_fields( 'myteam-plugin-settings' ); 
    $options = get_option('myteam-settings'); 
	?>
    
<div id="tabs-left">
<div>
<table cellpadding="5" cellspacing="5" class="wp-list-table widefat fixed posts" border="1" style="width:50%;">
  <tr>
    <td align="left" style="background-color:#f5f5f5;"><strong >Image Sizes</strong></td>
    <td nowrap>&nbsp;</td>
  </tr>
  <tr>
    <td width="150" align="leftt">Main Image Size</td>
    <td nowrap>Width: 
      <input name="myteam-settings[myteam_thumb_width]" type="text" value="<?php echo $options['myteam_thumb_width']; ?>" size="5" />
      Height:
      <input name="myteam-settings[myteam_thumb_height]" type="text" value="<?php echo $options['myteam_thumb_height']; ?>" size="5" />
      Crop: 
      <select name="myteam-settings[myteam_thumb_crop]">
        <option value="true" <?php selected($options['myteam_thumb_crop'], 'true' ); ?>>Yes</option>
        <option value="false" <?php selected($options['myteam_thumb_crop'], 'false' ); ?>>No</option>
        </select></td>
    
  </tr>
  <tr>
    <td width="150" align="leftt">Thumbnails Pager</td>
    <td nowrap>Width: 
      <input name="myteam-settings[myteam_tpimg_width]" type="text" value="<?php if(isset($options['myteam_tpimg_width'])):echo $options['myteam_tpimg_width']; endif; ?>" size="5" /> 
      Height: 
      <input name="myteam-settings[myteam_tpimg_height]" type="text" value="<?php if(isset($options['myteam_tpimg_height'])):echo $options['myteam_tpimg_height']; endif; ?>" size="5" /></td>
  </tr>
  <tr>
    <td width="150" align="leftt">Table Image Size</td>
    <td nowrap>Width: 
      <input name="myteam-settings[myteam_timg_width]" type="text" value="<?php if(isset($options['myteam_timg_width'])):echo $options['myteam_timg_width']; endif; ?>" size="5" />
      Height: 
      <input name="myteam-settings[myteam_timg_height]" type="text" value="<?php if(isset($options['myteam_timg_height'])):echo $options['myteam_timg_height']; endif; ?>" size="5" /></td>
  </tr>
  <tr>
    <td align="left">Social Icons</td>
    <td nowrap><select name="myteam-settings[myteam_single_social_icons]">
      <option value="round-32"  <?php selected($options['myteam_single_social_icons'], 'round-32' ); ?> >Round 32x32</option>
      <option value="round-24"  <?php selected($options['myteam_single_social_icons'], 'round-24' ); ?> >Round 24x24</option>
      <option value="round-20"  <?php selected($options['myteam_single_social_icons'], 'round-20' ); ?> >Round 20x20</option>
      <option value="round-16"  <?php selected($options['myteam_single_social_icons'], 'round-16' ); ?> >Round 16x16</option>
      <option value="square-32"  <?php selected($options['myteam_single_social_icons'], 'square-32' ); ?> >Square 32x32</option>
      <option value="square-24"  <?php selected($options['myteam_single_social_icons'], 'square-24' ); ?> >Square 24x24</option>
      <option value="square-20"  <?php selected($options['myteam_single_social_icons'], 'square-20' ); ?> >Square 20x20</option>
      <option value="square-16"  <?php selected($options['myteam_single_social_icons'], 'square-16' ); ?> >Square 16x16</option>
      </select></td>
  </tr>
  <tr>
    <td align="left">&nbsp;</td>
    <td nowrap>&nbsp;</td>
  </tr>
  <tr>
    <td width="150" align="left" style="background-color:#f5f5f5;"><strong>Email Settings</strong></td>
    <td nowrap>&nbsp;</td>
 </tr>
  <tr>
    <td width="150" align="leftt">mailto:active</td>
    <td nowrap><input name="myteam-settings[myteam_mailto]" type="checkbox" id="myteam-settings[myteam_mailto]" value="1" <?php if(isset($options['myteam_mailto'])) { echo 'checked="checked"';}  ?>></td>
    </tr>
  <tr>
    <td width="150" align="leftt">&nbsp;</td>
    <td nowrap>&nbsp;</td>
    </tr>
  
  <tr>
    <td width="150" align="left" style="background-color:#f5f5f5;"><strong>Single Page Settings</strong></td>
    <td nowrap>&nbsp;</td>
    </tr>
  <tr>
    <td align="left">Active:</td>
    <td nowrap><select name="myteam-settings[myteam_single_page]">
      <option value="true" <?php selected($options['myteam_single_page'], 'true' ); ?>>Yes</option>
      <option value="false" <?php selected($options['myteam_single_page'], 'false' ); ?>>No</option>
    </select></td>
  </tr>
  <tr>
    <td align="left">Layout:</td>
    <td nowrap><select name="myteam-settings[myteam_single_page_style]">
      <option value="none" <?php selected($options['myteam_single_page_style'], 'none' ); ?>>None</option>
      <option value="responsive" <?php selected($options['myteam_single_page_style'], 'responsive' ); ?>>Columns</option>
      <option value="vcard" <?php selected($options['myteam_single_page_style'], 'vcard' ); ?>>Information Card</option>
      </select></td>
  </tr>
  <tr>
    <td align="left" nowrap><label for="myteam-settings[myteam_single_show_posts]">Show Latest Posts</label></td>
    <td nowrap><input name="myteam-settings[myteam_single_show_posts]" type="checkbox" id="myteam-settings[myteam_single_show_posts]" value="1"  <?php if(isset($options['myteam_single_show_posts'])) { echo 'checked="checked"';}  ?>>
      <input type="text" name="myteam-settings[myteam_latest_title]" id="myteam-settings[myteam_latest_title]" value="<?php if(isset($options['myteam_latest_title'])) { echo $options['myteam_latest_title'];}  ?>"></td>
  </tr>
  <tr>
    <td align="left" valign="top" nowrap>Display:</td>
    <td nowrap><table border="0" cellspacing="5" cellpadding="5">
      <tr>
        <td nowrap><input name="myteam-settings[myteam_single_show_social]" type="checkbox" id="myteam-settings[myteam_single_show_social]" value="1" <?php if(isset($options['myteam_single_show_social'])) { echo 'checked="checked"';}  ?>>
          </td>
        <td nowrap><?php echo $myteam_labels['socialicons']['label']; ?></td>
      </tr>
      <tr>
        <td nowrap><input name="myteam-settings[myteam_single_show_smallicons]" type="checkbox" id="myteam-settings[myteam_single_show_smallicons]" value="1" <?php if(isset($options['myteam_single_show_smallicons'])) { echo 'checked="checked"';}  ?>>
         </td>
        <td nowrap><?php echo $myteam_labels['smallicons']['label']; ?></td>
      </tr>
      <tr>
        <td nowrap><input name="myteam-settings[myteam_single_show_photo]" type="checkbox" id="myteam-settings[myteam_single_show_photo]" value="1" <?php if(isset($options['myteam_single_show_photo'])) { echo 'checked="checked"';}  ?>></td>
        <td nowrap><?php echo $myteam_labels['photo']['label']; ?></td>
      </tr>
      <tr>
        <td nowrap><input name="myteam-settings[myteam_single_show_position]" type="checkbox" id="myteam-settings[myteam_single_show_position]" value="1" <?php if(isset($options['myteam_single_show_position'])) { echo 'checked="checked"';}  ?>></td>
        <td nowrap><?php echo $myteam_labels['position']['label']; ?></td>
        </tr>
      <tr>
        <td nowrap><input name="myteam-settings[myteam_single_show_email]" type="checkbox" id="myteam-settings[myteam_single_show_email]" value="1" <?php if(isset($options['myteam_single_show_email'])) { echo 'checked="checked"';}  ?>></td>
        <td nowrap><?php echo $myteam_labels['email']['label']; ?></td>
        </tr>
      <tr>
        <td nowrap><input name="myteam-settings[myteam_single_show_telephone]" type="checkbox" id="myteam-settings[myteam_single_show_telephone]" value="1" <?php if(isset($options['myteam_single_show_telephone'])) { echo 'checked="checked"';}  ?>></td>
        <td nowrap><?php echo $myteam_labels['telephone']['label']; ?></td>
        </tr>
      <tr>
        <td nowrap><input name="myteam-settings[myteam_single_show_location]" type="checkbox" id="myteam-settings[myteam_single_show_location]" value="1" <?php if(isset($options['myteam_single_show_location'])) { echo 'checked="checked"';}  ?>></td>
        <td nowrap><?php echo $myteam_labels['location']['label']; ?></td>
        </tr>
      <tr>
        <td nowrap><input name="myteam-settings[myteam_single_show_website]" type="checkbox" id="myteam-settings[myteam_single_show_website]" value="1" <?php if(isset($options['myteam_single_show_website'])) { echo 'checked="checked"';}  ?>></td>
        <td nowrap><?php echo $myteam_labels['website']['label']; ?></td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td align="left" style="background-color:#f5f5f5;"><strong>Feature Names</strong></td>
    <td nowrap>&nbsp;</td>
  </tr>
  <tr>
    <td align="left">Singular Name:</td>
    <td nowrap><input type="text" name="myteam-settings[myteam_name_singular]" value="<?php echo $options['myteam_name_singular']; ?>" /></td>
  </tr>
  <tr>
    <td align="left">Plural Name:</td>
    <td nowrap><input type="text" name="myteam-settings[myteam_name_plural]" value="<?php echo $options['myteam_name_plural']; ?>" /></td>
  </tr>
  <tr>
    <td align="left">Category:</td>
    <td nowrap><input type="text" name="myteam-settings[myteam_name_category]" value="<?php echo $options['myteam_name_category']; ?>" /></td>
  </tr>
  <tr>
    <td align="left">Slug:</td>
    <td nowrap><input type="text" name="myteam-settings[myteam_name_slug]" value="<?php echo $options['myteam_name_slug']; ?>" /></td>
  </tr>
  <tr>
    <td width="150" align="leftt">&nbsp;</td>
    <td nowrap>&nbsp;</td>
  </tr>
  </table>
</div>
<div id="single"></div>
<div id="names"></div>
</div>
	<input type="submit" class="button-secondary" value="<?php _e('Save Changes') ?>" style="margin:10px 0px 0px 315px;" />
</form>

<?php }
?>