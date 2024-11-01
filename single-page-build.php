<?php

/*
Author: Selvabalaji
Version: 1.0
Author URI:http://www.tbsin.com/
Packages : Member page
*/


function myteam_member_page($content) {
	$html = "";
	$infodiv = array();
	global $post;
	global $myteam_display_order;
	
	$options = get_option('myteam-settings');
		
	if(is_singular( 'myteam' ) && $options['myteam_single_page_style']!="none") {
		
	$truesocial = false; 
	if(isset($options['myteam_single_show_social']))	{
		$truesocial = true;
	}
	
	$html = "<div id='myteam-single-wrap'>";
	
	myteam_add_global_css();		
		
					
		//RESPONSIVE
		if($options['myteam_single_page_style']=="responsive") {		
			$html .=  '<div class="myteam-row-fluid">';		
			$html .=  '<div class="mt-col_3">';			
			$html .= myteam_get_image($post->ID);	//image				
			$html .= myteam_get_information($post->ID,true,array(),true);	//Information
			$html .= '<div>';
			$html .= myteam_get_social($post->ID,$truesocial);	//social links	
			//$html .= myteam_get_twitter($post->ID);	//twitter
			$html .= '</div></div><div class="mt-col_3c">';						
			$html .= $content;					
			$html .= myteam_latest_posts($post->ID); //show latest posts
			$html .= '</div></div>';
			}
		
		//VCARD
		if($options['myteam_single_page_style']=="vcard") {		
			$html .=  '<div class="myteam-vcard">';	
			$html .= '<div class="myteam-vcard-left">';	
			$html .= myteam_get_image($post->ID);	//image	
			$html .= '</div>';
						
			$html .=  '<div class="myteam-vcard-right">';							
			
			
			$infodiv['details'] = myteam_get_information($post->ID,true,array(),true);	//Information	
			$infodiv['social'] = '<div class="myteam-vcard-social">';
			$infodiv['social'] .= myteam_get_social($post->ID,$truesocial);	//social links		
			$infodiv['social'] .= '</div>';
			
			//ordering
			foreach ($myteam_display_order as $info) {
					if(isset($infodiv[$info])) {
					$html.=$infodiv[$info];
					}
				}
			
			
			$html .= '</div>';	
			$html .= '<div class="mt-clear-both">&nbsp;</div></div>';								
			$html .= $content;	
			//$html .= myteam_get_twitter($post->ID);	//twitter - currently not working			
			$html .= myteam_latest_posts($post->ID); //show latest posts			
			}
		$html .= "</div>";	
		return $html;
	}

	else {
		return $content;
	}
}

add_filter( 'the_content', 'myteam_member_page' );

?>