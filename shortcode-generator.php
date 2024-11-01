<?php

/*
Author: Selvabalaji
Version: 1.0
Author URI:http://www.tbsin.com/
Packages : shortcode generator page
*/

function myteam_shortcode_page_add() {
	
	$menu_slug = 'edit.php?post_type=myteam';
	$submenu_page_title = 'Shortcode Generator';
    $submenu_title = 'Shortcode Generator';
	$capability = 'manage_options';
    $submenu_slug = 'myteam_shortcode';
    $submenu_function = 'myteam_shortcode_page';
    $defaultp = add_submenu_page($menu_slug, $submenu_page_title, $submenu_title, $capability, $submenu_slug, $submenu_function);
	
	
	add_action($defaultp, 'myteam_enqueue_admin_js');
	
   }

function myteam_enqueue_admin_js() {
	
	//Slider JS
	wp_deregister_script( 'myteam-bxslider' );
	wp_register_script( 'myteam-bxslider', plugins_url( '/js/bxslider/jquery.bxslider.js', __FILE__ ),array('jquery'),false,false);
	wp_enqueue_script( 'myteam-bxslider' );	
	
	//Filter JS
	wp_deregister_script( 'myteam-filter' );
	wp_register_script( 'myteam-filter', plugins_url( '/js/filter.js', __FILE__ ),array('jquery'),false,false);
	wp_enqueue_script( 'myteam-filter' );
	
	wp_deregister_script( 'myteam-enhance-filter' );
	wp_register_script( 'myteam-enhance-filter', plugins_url( '/js/filter-enhance.js', __FILE__ ),array('jquery'),false,false);
	wp_enqueue_script( 'myteam-enhance-filter' );
	
	
	wp_deregister_script('myteamadmin');
	wp_register_script( 'myteamadmin', plugins_url( '/js/shortcode-builder.js' , __FILE__ ), array('jquery') );
	wp_enqueue_script( 'myteamadmin' );
	
	// in javascript, object properties are accessed as ajax_object.ajax_url, ajax_object.we_value
	wp_localize_script( 'myteamadmin', 'ajax_object',array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
	
	
	//All themes
	global $myteam_theme_names;	
	foreach ($myteam_theme_names as $themearray) {	
		foreach($themearray as $theme) {		
		wp_deregister_style( $theme['name']);
		wp_register_style($theme['name'], plugins_url($theme['link'], __FILE__ ),array(),false,false);
		wp_enqueue_style($theme['name'] );	
		}
	}
	
			
				

	//global styles
	wp_deregister_style( 'myteam-global-style' );
	wp_register_style( 'myteam-global-style', plugins_url( '/css/global.css', __FILE__ ),array(),false,false);
	wp_enqueue_style( 'myteam-global-style' );	
			
	//small icons
	wp_deregister_style( 'myteam-smallicons' );
	wp_register_style( 'myteam-smallicons', plugins_url( '/css/font-awesome/css/font-awesome.min.css', __FILE__ ),array(),false,false);
	wp_enqueue_style( 'myteam-smallicons' );	
	
}



add_action('wp_ajax_myteam', 'myteam_run_preview');

function myteam_run_preview() {	
	
	$orderby = $_POST['porder'];
	$limit = $_POST['plimit'];
  $idsfilter = $_POST['pidsfilter'];
	$category = $_POST['pcategory'];
	$url =  $_POST['purl'];
	$layout = $_POST['playout'];
	$style = $_POST['pstyle'];
	$display = $_POST['pdisplay']; 
	$img = $_POST['pimg']; 	 
	$html = create_myteam($orderby,$limit,$idsfilter,$category,$url,$layout,$style,$display,$img);
	
	echo $html;
	die(); // this is required to return a proper result
}



function myteam_shortcode_page() { 
	$options = get_option('myteam-settings');	
	$categories = $options['myteam_name_category'];
	
	global $myteam_labels;
	global $myteam_theme_names;

?>

<h1>Shortcode Generator</h1>
<table cellpadding="5" cellspacing="5">
  <tr>
    <td width="20%" valign="top"><div class="postbox" style="width:360px;">
        <form id="shortcode_generator" style="padding:20px;">
          <h2>What entries do you want to display:</h2>
          <p>
            <label for="category"><?php echo $categories; ?>:</label>
            <select id="category" name="category" onChange="myteamshortcodegenerate()">
              <option value="0">All</option>
              <?php 
		
				 $terms = get_terms("myteam-categories");
				 $count = count($terms);
				 if ( $count > 0 ){
					 
					 foreach ( $terms as $term ) {
					    echo "<option value='".$term->slug."'>".$term->name."</option>";
						 }
					 
				 }
		
		?>
            </select>
          </p>
          <p>
            <label for="orderby">Order By:</label>
            <select id="orderby" name="orderby" onChange="myteamshortcodegenerate()">
              <option value="none">Default (Order Field)</option>
              <option value="title">Name</option>
              <option value="ID">ID</option>
              <option value="date">Date</option>
              <option value="modified">Modified</option>
              <option value="rand">Random</option>
            </select>
          </p>
          <p>
            <label for="limit">Number of entries to display:</label>
            <input size="3" id="limit" name="limit" type="text" value="0" onChange="myteamshortcodegenerate()" />
            <span class="howto" style=" line-height:20px;"> (Leave blank or 0 to display all)</span></p>
          </p>
          <p>
            <label for="idsfilter">IDs to display:</label>
            <input size="10" id="idsfilter" name="idsfilter" type="text" value="0" onChange="myteamshortcodegenerate()" />
            <span class="howto" style=" line-height:20px;"> (Comma sperated ID values of specific entries you want to display. Example: 7,11)</span></p>
          </p>
          <h2>What information do you want to display:</h2>
		  <div style="border:1px solid #ccc; background:#FFF; padding:10px;">
          <table width="100%" border="0" cellspacing="5" cellpadding="0">
            <tr>
              <td><input name="name" type="checkbox" id="name" onChange="myteamshortcodegenerate()" value="name" checked>
                <label for="name"><?php echo $myteam_labels['name']['label']; ?></label></td>
              <td><input name="photo" type="checkbox" id="photo" onChange="myteamshortcodegenerate()" value="photo" checked>
                <label for="photo"><?php echo $myteam_labels['photo']['label']; ?></label></td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td><input name="smallicons" type="checkbox" id="smallicons" value="smallicons" onChange="myteamshortcodegenerate()">
                <label for="smallicons"><?php echo $myteam_labels['smallicons']['label']; ?></label>
                &nbsp;</td>
              <td colspan="2"><span class="howto" style=" line-height:20px;">Will display small icons before the information</span></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td><input name="social" type="checkbox" id="social" onChange="myteamshortcodegenerate()" value="social" checked>
                <label for="social"><?php echo $myteam_labels['socialicons']['label']; ?></label></td>
              <td><label for="position">
                <input name="position" type="checkbox" id="position" onChange="myteamshortcodegenerate()" value="position" checked>
                <?php echo $myteam_labels['position']['label']; ?></label></td>
              <td><label for="location">
                <input name="location" type="checkbox" id="location" value="location" onChange="myteamshortcodegenerate()">
                <?php echo $myteam_labels['location']['label']; ?> &nbsp;</label></td>
            </tr>
            <tr>
              <td><input name="email" type="checkbox" id="email" onChange="myteamshortcodegenerate()" value="email" checked>
                <label for="email"><?php echo $myteam_labels['email']['label']; ?></label>
                &nbsp;</td>
              <td><input name="website" type="checkbox" id="website" value="website" onChange="myteamshortcodegenerate()">
                <label for="website"><?php echo $myteam_labels['website']['label']; ?></label>
                &nbsp;</td>
              <td><input name="telephone" type="checkbox" id="telephone" value="telephone" onChange="myteamshortcodegenerate()">
                <label for="telephone"><?php echo $myteam_labels['telephone']['label']; ?> </label></td>
            </tr>
            <tr>
              <td></td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
          </table>
		  </div>
          <p>
            <label for="singleurl">Single Page Link: </label>
            <select id="singleurl" name="singleurl" onChange="myteamshortcodegenerate()">
              <option value="inactive">Inactive</option>
              <option value="active">Active</option>
            </select>
            <span class="howto" style=" line-height:20px;">Only considered if Single Page is Active on Settings</span></p>
          <h2>How you want it to look like:</h2>
          <div style="border:1px solid #ccc; background:#FFF; padding:10px;"> Load a Layout Preset: <br>
            <select name="preset" id="preset" onChange="myteampreset()">
              <option value="none">None</option>
              <option value="polaroid">Polaroid Grid </option>
              <option value="white-polaroid">White Polaroid Grid </option>
              <option value="gray-card-grid">Gray Card Grid </option>
              <option value="circle-grid">Circle Centered Grid</option>
              <option value="content-right-simple-grid" selected>Simple Grid with content right</option>
              <option value="content-below-simple-grid">Simple Grid with content below</option>
              <option value="hover-circle-white-grid">Circle Images With Info on Hover I</option>
              <option value="hover-circle-grid">Circle Images With Info on Hover II</option>
              <option value="hover-square-grid">Squared Images With Info on Hover</option>
              <option value="simple-table">Simple Table Layout</option>
            </select>
            <span class="howto" style=" line-height:20px;">Choosing a  preset will automaticaly select predefined values for the visuals.
            You can then adjust the options to your needs.</span></div>
          <p>
            <label for="layout">Layout:</label>
            <select id="layout" name="layout" onChange="myteamshortcodegenerate()">
              <option value="grid">Grid</option>
              <option value="hover">Hover Grid</option>
              <option value="pager">Thumbnails Pager</option>
              <option value="table">Table</option>
            </select>
          </p>
          <div id="columnsdiv">
            <p>
              <label for="columns">Columns:</label>
              <select name="columns" id="columns" onChange="myteamshortcodegenerate()">
                <option value="normal-float">Normal Float</option>
                <option value="1-column">1 Column</option>
                <option value="2-columns" selected>2 Columns</option>
                <option value="3-columns">3 Columns</option>
                <option value="4-columns">4 Columns</option>
                <option value="5-columns">5 Columns</option>
                <option value="6-columns">6 Columns</option>
              </select>
            </p>
          </div>
          <div id="griddiv">
            <div style="border:1px solid #ccc; background:#FFF; padding:5px;">
              <label for="filtergrid"><?php echo $categories.' '.$myteam_labels['filter']['label']; ?>:</label>
              <select name="filtergrid" id="filtergrid" onChange="myteamshortcodegenerate()">
                <option value="inactive" selected>Inactive</option>
                <option value="filter">Active - Hide Filter</option>
                <option value="enhance-filter">Active - Enhance Filter</option>
              </select>
              <span class="howto" style=" line-height:20px;">When active, a jQuery Category filter will display above the Grid. Only works when all categories are displaying. </span> </div>
            <p>Theme:
              <label for="grid-styling"></label>
              <select name="grid-styling" id="grid-styling" onChange="myteamshortcodegenerate()">
                <?php 
		   foreach ($myteam_theme_names['grid'] as $tbstyle) {
		   ?>
                <option value="<?php echo $tbstyle['key'] ?>"><?php echo $tbstyle['label'] ?></option>
                <?php } ?>
              </select>
            </p>
            <p>
              <label for="composition">Composition:</label>
              <select name="composition" id="composition" onChange="myteamshortcodegenerate()">
                <option value="img-left" selected>Image Left - Content Right</option>
                <option value="img-right">Content Right - Image Left</option>
                <option value="img-above">Image Above - Content Below</option>
              </select>
            </p>
          </div>
          <div id="pagerdiv">
            <p>Theme:
              <label for="pager-styling"></label>
              <select name="pager-styling" id="pager-styling" onChange="myteamshortcodegenerate()">
                <?php 
		   foreach ($myteam_theme_names['pager'] as $tbstyle) {
		   ?>
                <option value="<?php echo $tbstyle['key'] ?>"><?php echo $tbstyle['label'] ?></option>
                <?php } ?>
              </select>
            </p>
            <p>
              <label for="pagercomposition">General Composition:</label>
              <select name="pagercomposition" id="pagercomposition" onChange="myteamshortcodegenerate()">
                <option value="thumbs-left" selected>Thumnails Left - Content Right</option>
                <option value="thumbs-right">Content Left - Thumbnails Right</option>
                <option value="thumbs-below">Content Above - Thumbnails Below</option>
              </select>
            </p>
            <p>
              <label for="pagerimgcomposition">Image Composition:</label>
              <select name="pagerimgcomposition" id="pagerimgcomposition" onChange="myteamshortcodegenerate()">
                <option value="img-left">Image Left - Content Right</option>
                <option value="img-right">Content Right - Image Left</option>
                <option value="img-above" selected>Image Above - Content Below</option>
              </select>
            </p>
          </div>
          <div id="tablediv">
            <p>Theme:
              <label for="table-styling"></label>
              <select name="table-styling" id="table-styling" onChange="myteamshortcodegenerate()">
                <?php 
		   foreach ($myteam_theme_names['table'] as $tbstyle) {
		   ?>
                <option value="<?php echo $tbstyle['key'] ?>"><?php echo $tbstyle['label'] ?></option>
                <?php } ?>
              </select>
            </p>
          </div>
          <div id="hoverdiv">
            <div style="border:1px solid #FFF; background:#FFF; padding:5px;">
              <label for="filter"><?php echo $categories.' '.$myteam_labels['filter']['label']; ?>:</label>
              <select name="filterhover" id="filterhover" onChange="myteamshortcodegenerate()">
                <option value="filter">Active - Hide Effect</option>
                <option value="enhance-filter">Active - Enhance Effect</option>
                <option value="inactive" selected>Inactive</option>
              </select>
              <span class="howto" style=" line-height:20px;">When active, a jQuery Category filter will display above the Grid. Only works when all categories are displaying. </span> </div>
            <p>Theme:
              <label for="hover-styling"></label>
              <select name="hover-styling" id="hover-styling" onChange="myteamshortcodegenerate()">
                <?php 
		   foreach ($myteam_theme_names['hover'] as $tbstyle) {
		   ?>
                <option value="<?php echo $tbstyle['key'] ?>"><?php echo $tbstyle['label'] ?></option>
                <?php } ?>
              </select>
            </p>
          </div>
          <div id="imgdiv">
            <p>Image Shape:
              <select id="imgstyle" name="imgstyle" onChange="myteamshortcodegenerate()">
                <option value="img-square">Square (normal)</option>
                <option value="img-rounded">Rounded Corners</option>
                <option value="img-circle">Circular</option>
              </select>
            </p>
            <p>Image Effect:
              <select id="imgeffect" name="imgeffect" onChange="myteamshortcodegenerate()">
                <option value="">None</option>
                <option value="img-grayscale">Grayscale</option>
                <option value="img-shadow">Shadow Highlight</option>
                <option value="img-white-border">White Border</option>
                <option value="img-grayscale-shadow">Shadow Highlight & Grayscale</option>
              </select>
            </p>
          </div>
          <p>
            <label for="textalign"> Text-Align:</label>
            <select name="textalign" id="textalign" onChange="myteamshortcodegenerate()">
              <option value="text-left" selected>Left</option>
              <option value="text-right" >Right</option>
              <option value="text-center">Center</option>
            </select>
          </p>
		  <div style="border:1px solid #ccc; background:#FFF; padding:10px;">
          <div id="imgsize" style="border-top:1px dashed #CCC;">
            <p>Image Size Override:
              <label for="img"></label>
              <input type="text" name="img" id="img" onChange="myteamshortcodegenerate()">
              <br>
              <span class="howto" style=" line-height:20px;">Leave blank to use default values.<br>
              In case you want to override the default image size settings, use this field to put the width and height values in the following format: width,height <br>
              ex. 100,100. <br>
              Width value will prevail if images don't have exactly this size.</span></p>
          </div>
		  </div>
        </form>
      </div></td>
    <td width="80%" valign="top"><h3>Shortcode</h3>
      Use this shortcode to display the list of logos in your posts or pages! Just copy this piece of text and place it where you want it to display.
      <div id="shortcode" style="padding:10px; background-color:#ccc;"></div>
      <h3>PHP Function</h3>
      Use this PHP function to display the list of logos directly in your theme files!
      <div id="phpcode" style="padding:10px; background-color:#f5f5f5;"> </div>
      <h3>Preview</h3>
      <div id="preview-warning" style="padding:5px; margin:10px 0px 30px 0px; border-radius:5px; font-weight:bold; font-size:0.9em; border:1px solid #CCC; background-color:#F5f5f5;">Attention! <br>
        This is a preview only. The visuals might differ after applying the shortcode or function on your theme due to extra styling rules that your Theme might have or the available space. <br>
        Some combination of settings don't work well, as they don't fit together visually or are conflictual. </div>
      <div id="preview"> </div>
      <div style="clear:both; margin:20px 10px;"> <strong>Current Seetings Shortcode:</strong>
        <div id="shortcode2" style="padding:10px; background-color:#f5f5f5;"></div>
      </div></td>
  </tr>
</table>
<?php } 
?>
