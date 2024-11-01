<?php
/*
Author: Selvabalaji
Version: 1.0
Author URI:http://www.tbsin.com/
Packages : UTILS
*/

function myteam_strip_http($url) {	
	$url = preg_replace('#^https?://#', '', $url);
    return $url;
}

//To Show styled messages
function myteam_message($msg) { ?>
<div id="message" class="updated"><p><?php echo $msg; ?></p></div>
<?php	
}

function myteam_get_img_style($style) {
	
	global $myteam_imgstyles;
	
	$css = "";
	$styles = explode(',',$style);
	
	foreach ($styles as $st) {
	if(array_key_exists($st,$myteam_imgstyles)) {
		$css .= $myteam_imgstyles[$st].' ';
		}
	}
	
	return $css;
}

function myteam_get_info_style($style) {
	
	global $myteam_infostyles;
	
	$css = "";
	$styles = explode(',',$style);
	
	foreach ($styles as $st) {
	if(array_key_exists($st,$myteam_infostyles)) {
		$css .= $myteam_infostyles[$st].' ';
		}
	}
	
	return $css;
}


function myteam_get_box_style($style) {
	
	global $myteam_boxstyles;
	
	$css = "";
	$styles = explode(',',$style);
	
	foreach ($styles as $st) {
	if(array_key_exists($st,$myteam_boxstyles)) {
		$css .= $myteam_boxstyles[$st].' ';
		}
	}
	
	return $css;
}

function myteam_get_innerbox_style($style) {
	
	global $myteam_innerboxstyles;
	
	$css = "";
	$styles = explode(',',$style);
	
	foreach ($styles as $st) {
	if(array_key_exists($st,$myteam_innerboxstyles)) {
		$css .= $myteam_innerboxstyles[$st].' ';
		}
	}
	
	return $css;
}


function myteam_get_wrap_style($style) {
	
	global $myteam_wrapstyles;
	
	$css = "";
	$styles = explode(',',$style);
	
	foreach ($styles as $st) {
	if(array_key_exists($st,$myteam_wrapstyles)) {
		$css .= $myteam_wrapstyles[$st].' ';
		}
	}
	
	return $css;
}


function myteam_get_txt_style($style) {
	global $myteam_txtstyles;
	
	$css = "";
	$styles = explode(',',$style);
	
	foreach ($styles as $st) {
	if(array_key_exists($st,$myteam_txtstyles)) {
		$css .= $myteam_txtstyles[$st].' ';
		}
	}
	
	return $css;
}

function myteam_get_pager_style($style) {
	global $myteam_pagerstyles;
	
	$css = "";
	$styles = explode(',',$style);
	
	foreach ($styles as $st) {
	if(array_key_exists($st,$myteam_pagerstyles)) {
		$css .= $myteam_pagerstyles[$st].' ';
		}
	}
	
	return $css;
}

function myteam_get_pager_box_style($style) {
	global $myteam_pagerboxstyles;
	
	$css = "";
	$styles = explode(',',$style);
	
	foreach ($styles as $st) {
	if(array_key_exists($st,$myteam_pagerboxstyles)) {
		$css .= $myteam_pagerboxstyles[$st].' ';
		}
	}
	
	return $css;
}

function myteam_get_themes($style,$layout) {
	global $myteam_theme_names;
	
	$themearray = $myteam_theme_names[$layout];
		
	$css = "default";
	$styles = explode(',',$style);
	
	foreach ($styles as $st) {
	if(array_key_exists($st,$themearray)) {
		$css = $themearray[$st]['key'];
		}
	}
		
	return $css;
}


?>