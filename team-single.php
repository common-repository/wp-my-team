<?php
/*
Author: Selvabalaji
Version: 1.1
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
			if($options['myteam_single_page_share'] == '1'){
			$html .='<!-- AddThis Button BEGIN -->
                <a class="addthis_button" href="http://www.addthis.com/bookmark.php?v=300&amp;pubid=ra-51f5ff9515d6d31e"><img alt="example image" src="http://s7.addthis.com/static/btn/v2/lg-share-en.gif" width="125" height="16" style="border:0"/></a>
                <script type="text/javascript">var addthis_config = {"data_track_addressbar":true};</script>
                <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-51f5ff9515d6d31e"></script>
                <!-- AddThis Button END -->';
			
			  }             

			
							
			$html .= myteam_latest_posts($post->ID); //show latest posts
			$html .= '</div></div>';
			}
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
			
			if($options['myteam_single_page_share'] == '1'){
			$html .='<!-- AddThis Button BEGIN -->
                <a class="addthis_button" href="http://www.addthis.com/bookmark.php?v=300&amp;pubid=ra-51f5ff9515d6d31e"><img alt="example image" src="http://s7.addthis.com/static/btn/v2/lg-share-en.gif" width="125" height="16" style="border:0"/></a>
                <script type="text/javascript">var addthis_config = {"data_track_addressbar":true};</script>
                <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-51f5ff9515d6d31e"></script>
                <!-- AddThis Button END -->';
			
			  }             

			//$html .= myteam_get_twitter($post->ID);	//twitter - currently not working			
			$html .= myteam_latest_posts($post->ID); //show latest posts			
			}
		$html .= "</div>";	
		return $html;
	} else {
		return $content;
	}
}
add_filter( 'the_content', 'myteam_member_page' );
?>