<?php

function register_myteam() {
	$options = get_option('myteam-settings');
	$name = $options['myteam_name_singular'];
	$nameplural = $options['myteam_name_plural'];
	$slug = $options['myteam_name_slug'];
	$singlepage = $options['myteam_single_page'];
    $labels = array( 
        'name' 					=> _x( $nameplural, 'myteam' ),
        'singular_name' 		=> _x( $name, 'myteam' ),
        'add_new'				=> _x( 'Add New '.$name, 'myteam' ),
        'add_new_item' 			=> _x( 'Add New '.$name, 'myteam' ),
        'edit_item' 			=> _x( 'Edit '.$name, 'myteam' ),
        'new_item' 				=> _x( 'New '.$name, 'myteam' ),
        'view_item' 			=> _x( 'View '.$name, 'myteam' ),
        'search_items' 			=> _x( 'Search '.$nameplural, 'myteam' ),
        'not_found'				=> _x( 'No '.$nameplural.' found', 'myteam' ),
        'not_found_in_trash' 	=> _x( 'No '.$nameplural.' found in Trash', 'myteam' ),
        'parent_item_colon' 	=> _x( 'Parent '.$name.':', 'myteam' ),
        'menu_name' 			=> _x( $nameplural, 'myteam' ),
    );
	
	$singletrue = true;
	if($singlepage=="false") { $singletrue = false; }
	
    $args = array( 
        'labels' 				=> $labels,
		'hierarchical' 			=> false,        
		'supports' 				=> array( 'title', 'thumbnail', 'custom-fields', 'editor','page-attributes'),
        'public' 				=> $singletrue,
        'show_ui' 				=> true,
        'show_in_menu' 			=> true,       
        'show_in_nav_menus' 	=> true,
        'publicly_queryable' 	=> true,
        'exclude_from_search' 	=> true,
        'has_archive' 			=> true,
        'query_var' 			=> true,
        'can_export' 			=> true,
        'rewrite' 				=> true,
        'capability_type' 		=> 'post',
		'menu_icon' 			=> plugins_url('img/team-icon.png', __FILE__ ),
		'rewrite' 				=> array( 'slug' => $slug )
    );
    register_post_type( 'myteam', $args );
}

function myteam_add_thumbnails_img() {
    global $myteam_wp_theme_features;
   if($myteam_wp_theme_features['post-thumbnails']==1) {
		return;		
	 }	
	  if(is_array($myteam_wp_theme_features['post-thumbnails'][0]) && count($myteam_wp_theme_features['post-thumbnails'][0]) > 0) {
		array_push($myteam_wp_theme_features['post-thumbnails'][0],'myteam');
		return;
		}
	if( empty($myteam_wp_theme_features['post-thumbnails']) ) {
        $myteam_wp_theme_features['post-thumbnails'] = array( array('myteam') );
		return;
	}
}

$myteam_crop 	= false;
$myteam_options = get_option('myteam-settings');

if($myteam_options['myteam_thumb_crop']=="true") {
	$myteam_crop = true;
}
add_image_size( 'myteam-thumb', $myteam_options['myteam_thumb_width'], $myteam_options['myteam_thumb_height'], $myteam_crop);

function myteam_build_taxonomies() {
	$options = get_option('myteam-settings');	
	$categories = $options['myteam_name_category'];
    register_taxonomy( 'myteam-categories', 'myteam', array( 'hierarchical' => true, 'label' => $categories, 'query_var' => true, 'rewrite' => true ) );
}

function myteam_change_default_title( $title ){
	$screen = get_current_screen();
	$options = get_option('myteam-settings');	
	$name = $options['myteam_name_singular'];
	$nameplural = $options['myteam_name_plural'];
     if  ( 'myteam' == $screen->post_type ) {
          $title = 'Enter '.$name.' Name Here';
     } 
     return $title;
}

function myteam_add_social_metabox() {
	global $myteam_labels;
	$title = $myteam_labels['titles']['social'];
	add_meta_box( 'myteam-social-metabox',$title, 'myteam_social_metabox', 'myteam', 'normal', 'high' );
}

function myteam_add_info_metabox() {
	global $myteam_labels;
	$title = $myteam_labels['titles']['info'];
	add_meta_box( 'myteam-info-metabox', $title, 'myteam_info_metabox', 'myteam', 'normal', 'high' );
}

function register_myteam_settings() {
	register_setting( 'myteam-plugin-settings', 'myteam-settings');
}

function myteam_defaults() {
	$team_setting = get_option('myteam-settings');

    if((!is_array($team_setting)) || !isset($team_setting['myteam_empty'])) {
		delete_option('myteam-settings'); 
		$arr = array("myteam_name_singular" 		=> "Member",
					"myteam_name_plural" 			=> "Team",
					"myteam_name_slug"				=> "team",
					"myteam_name_category" 			=> "Groups",
					"myteam_thumb_width" 			=> "160",
					"myteam_thumb_height" 			=> "160",
					"myteam_thumb_crop" 			=> "false",
					"myteam_single_page" 			=> "true", 
					"myteam_single_page_style" 		=> "none", 
					"myteam_single_page_share" 		=> "1", 
					"myteam_single_show_posts" 		=> "false",
					"myteam_single_social_icons"	=> "round-20",
					"myteam_empty" 					=> "0",
					"myteam_twitter_title" 			=> "Latest Tweets", 
					"myteam_latest_title" 			=> "Latest Posts",
					"myteam_single_show_photo" 		=> "",
					"myteam_single_show_social" 	=> "",
					"myteam_single_show_position" 	=> "",
					"myteam_mailto" 				=> "",
					"myteam_timg_width" 			=> "50",
					"myteam_timg_height" 			=> "50",
					"myteam_tpimg_width" 			=> "50",
					"myteam_tpimg_height" 			=> "50",					
		);
		update_option('myteam-settings', $arr);
	}
}

function myteam_admin_page() {
	$menu_slug = 'edit.php?post_type=myteam';
	$submenu_page_title = 'Settings';
    $submenu_title = 'Settings';
	$capability = 'manage_options';
    $submenu_slug = 'myteam_settings';
    $submenu_function = 'myteam_settings_page';
    $defaultp = add_submenu_page($menu_slug, $submenu_page_title, $submenu_title, $capability, $submenu_slug, $submenu_function);
   }

function myteam_save_info( $post_id ) {
	global $post;
	// Skip auto save
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return $post_id;
    }
	if(isset($post)) {
		if ($post->post_type == 'myteam') {
			if( $_POST ) {
				update_post_meta( $post->ID, '_mtemail', $_POST['_mtemail'] );
				update_post_meta( $post->ID, '_mttel', $_POST['_mttel'] );
				update_post_meta( $post->ID, '_mtposition', $_POST['_mtposition'] );
				update_post_meta( $post->ID, '_mtuser', $_POST['_mtuser'] );
				update_post_meta( $post->ID, '_mttwitusername', $_POST['_mttwitusername'] );
				update_post_meta( $post->ID, '_mtpersonal', $_POST['_mtpersonal'] );
				update_post_meta( $post->ID, '_mtlocation', $_POST['_mtlocation'] );
			}
		}
	}
}

function myteam_save_social( $post_id ) {
	global $post;
	if(isset($post)) {
		if ($post->post_type == 'myteam') {
			if( $_POST ) {
				update_post_meta( $post->ID, '_mtlinkedin', $_POST['_mtlinkedin'] );
				update_post_meta( $post->ID, '_mtfacebook', $_POST['_mtfacebook'] );
				update_post_meta( $post->ID, '_mttwitter', $_POST['_mttwitter'] );
				update_post_meta( $post->ID, '_mtgplus', $_POST['_mtgplus'] );
				update_post_meta( $post->ID, '_mtpinterest', $_POST['_mtpinterest'] );
				update_post_meta( $post->ID, '_mtyoutube', $_POST['_mtyoutube'] );
				update_post_meta( $post->ID, '_mtvimeo', $_POST['_mtvimeo'] );
				update_post_meta( $post->ID, '_mtdribbble', $_POST['_mtdribbble'] );
				update_post_meta( $post->ID, '_mtemailico', $_POST['_mtemailico'] );
				update_post_meta( $post->ID, '_mtinstagram', $_POST['_mtinstagram'] );
			}
		}
	}
}

function myteam_columns_content($column_name, $post_ID) {
	global $post;
    if ($post->post_type == 'myteam') {
		if ($column_name == 'featured_image') {		
			echo get_the_post_thumbnail($post_ID, array(50,50));		
		}
	   if ($column_name == 'order') {		
			echo $post->menu_order;		
		}
	}
}

function shortcode_myteam( $atts ) {	
	if (!is_array($atts)) { $atts = array(); }
	$orderby 	= 	(array_key_exists('orderby', $atts) ? $atts['orderby'] : "menu_order");
	$limit 		= 	(array_key_exists('limit', $atts) ? $atts['limit'] : 0);
	$idsfilter 	= 	(array_key_exists('idsfilter', $atts) ? $atts['idsfilter'] : "0");
	$category 	= 	(array_key_exists('category', $atts) ? $atts['category'] : "0");
	$url 		=  	(array_key_exists('url', $atts) ? $atts['url'] : "inactive");
	$layout 	= 	(array_key_exists('layout', $atts) ? $atts['layout'] : "grid");
	$style 		= 	(array_key_exists('style', $atts) ? $atts['style'] : "img-square,normal-float");
	$display 	= 	(array_key_exists('display', $atts) ? $atts['display'] : "photo,position,email"); 
	$img 		= 	(array_key_exists('img', $atts) ? $atts['img'] : ""); 
	$html = generate_myteam($orderby,$limit,$idsfilter,$category,$url,$layout,$style,$display,$img);
	return $html;	
}

function generate_myteam($orderby="menu_order",$limit=-1,$idsfilter="0",$category="",$url="inactive",$layout="grid",$style="float-normal",$display="photo,name,position,email",$imgwo="") {
	
	myteam_add_global_css();
	$html		= "";
	$thumbsize 	= "myteam-thumb";

	global $post;
	global $myteam_labels;
	$options = get_option('myteam-settings');
	
	if($orderby	==	'none') {
		$orderby = 'menu_order';
	};
	
	$ascdesc = 'DESC';
	if($orderby == 'title' || $orderby == 'menu_order') {
		$ascdesc = 'ASC';
	};
	
	$postsperpage 	= 	-1;
	$nopaging		=	true;

	if($limit >= 1) { 
		$postsperpage = $limit;
		$nopaging = false;
	}
	
	$display 	= explode(',',$display);
	$socialshow = false;
	
	if(in_array('social',$display)) {
		$socialshow = true;
	}
	
	$imgwidth = "";
	if($imgwo!=""){
		$imgwidth = explode(',',$imgwo);
	}
	
	if(in_array('smallicons',$display)) {
		myteam_add_smallicons_css();	
	}

	$args = array( 'post_type' 			=> 'myteam',
				   'myteam-categories' 	=> $category, 
				   'orderby' 			=> $orderby, 
				   'order' 				=> $ascdesc, 
				   'posts_per_page'		=> $postsperpage, 
				   'nopaging'			=> $nopaging
				   );
	
	if($idsfilter != '0' && $idsfilter != '') {
		$postarray = explode(',', $idsfilter);
		if($postarray[0]!='') {
			$args['post__in'] = $postarray;
		}
	} 

	$loop = new WP_Query( $args );
	if($layout=='table') {
		$html .= myteam_build_table_layout($loop,$url,$display,$style);
	} 
	
	if($layout=='pager' || $layout=='thumbnails' ) {
		global $myteam_pager_count;
		myteam_pager_layout();
		$imgstyle = myteam_get_img_style($style);
		$txtstyle = myteam_get_txt_style($style);
		$pagerstyle = myteam_get_pager_style($style);
		$pagerboxstyle = myteam_get_pager_box_style($style);
		$infostyle = myteam_get_info_style($style);	
		$theme = myteam_get_themes($style,'pager');	
		myteam_add_theme($theme,'pager');
		$thumbshtml ="";
		$previewhtml ="";
		$ic = 0;
		$lmyteam_options = get_option('myteam-settings');
		$dwidth = $lmyteam_options['myteam_thumb_width'];	
		
		if(is_array($imgwidth)) {
			$thumbsize = $imgwidth;
			$dwidth = $thumbsize[0];
		}
		
		while ( $loop->have_posts() ) : $loop->the_post(); 
			$title = the_title_attribute( 'echo=0' );	
			if ( has_post_thumbnail() && in_array('photo',$display)) :     
				$image 		= 	wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), $thumbsize );
				$width 		= 	$thumbsize[0];			
				$twidth 	= 	$options['myteam_tpimg_width'];
				$theight 	= 	$options['myteam_tpimg_height'];
				$thumb 		= 	wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ),array($twidth,$theight),true); 			
			
				$previewhtml .='<li><div class="myteam-box">';
				if($options['myteam_single_page']=="true" && $url =="active") {
					$previewhtml .='<div class="myteam-box-photo '.$imgstyle.'"><a href="'.get_permalink($post->ID).'"><img src="'.$image[0].'" width="'.$width.'" /></a></div>';
				} else {
					$previewhtml .='<div class="myteam-box-photo '.$imgstyle.'"><img src="'.$image[0].'" width="'.$width.'" /></div>';
				}
				$previewhtml .= "<div class='myteam-box-info ".$infostyle." ".$txtstyle."'>";
			
				if (in_array('name',$display)) : 
					if($options['myteam_single_page']=="true" && $url =="active") {
						$previewhtml .='<div class="myteam-box-title"><a href="'.get_permalink($post->ID).'">'.$title.'</a></div>';
					} 	else {
						$previewhtml .= "<div class='myteam-box-title'>".$title."</div>";
					}
				endif;
			
				if ($socialshow) : 		
					$previewhtml .="<div class='myteam-box-social'>".myteam_get_social(get_the_ID(),$socialshow)."</div>";
				endif;
			
					$previewhtml .="<div class='myteam-box-details'>".myteam_get_information(get_the_ID(),true,$display,false)."</div>";
					$previewhtml .="</div></div></li>";
					$thumbshtml  .='<div class="myteam-pager-thumbnail '.$imgstyle.'"><a data-slide-index="'.$ic.'" href=""><img src="'.$thumb[0].'" width="'.$thumb[1].'" /></a></div>';		  
				$ic++;	 
				endif;
			endwhile;
		
			$wrapclass = 'myteam-pager-wrap';
			if($theme!="default") {  $wrapclass = "myteam-pager-".$theme."-wrap";  }
			$html .= '<div class="myteam-pager-wrap '.$wrapclass.'">';
			$html .= '<div class="'.$pagerboxstyle.'"><ul class="myteam-bxslider-'.$myteam_pager_count.'">';
			$html .= $previewhtml;
			$html .= '</ul></div>';
			$html .= '<div id="myteam-bx-pager-'.$myteam_pager_count.'" class="'.$pagerstyle.'">';
			$html .= $thumbshtml;
			$html .= '</div>';
			$html .= '<div class="mt-clear-both"></div></div>';
			$myteam_pager_count++;
	}
	
	if($layout=='grid') {
		$imgstyle 		= myteam_get_img_style($style);
		$txtstyle 		= myteam_get_txt_style($style);
		$boxstyle 		= myteam_get_box_style($style);
		$innerboxstyle 	= myteam_get_innerbox_style($style);
		$infostyle 		= myteam_get_info_style($style);	
		$wrapstyle 		= myteam_get_wrap_style($style);
		$theme 			= myteam_get_themes($style,'grid');
	
		myteam_add_theme($theme,'grid');	
		
		$html .="<div class='myteam-wrap ".$wrapstyle."'>";	
			if (in_array('filter',$display) || in_array('enhance-filter',$display) ) {
			if (in_array('filter',$display)) {
				myteam_filter_code();
				$html .= "<ul id='mt-filter-nav'>";
			}
			if (in_array('enhance-filter',$display)) {
				myteam_enhance_filter_code();
				$html .= "<ul id='mt-enhance-filter-nav'>";
			}
			$html .= "<li id='mt-all'>".$myteam_labels['filter']['all-entries-label']."</li>";
			 $terms = get_terms("myteam-categories");
			 $count = count($terms);
			 if ( $count > 0 ){		 
				 foreach ( $terms as $term ) {
					$html .= "<li id='mt-".$term->slug."'>".$term->name."</li>";
					 }		 
			 }
			$html .= "</ul>";
			$boxstyle .=" myteam-filter-active";
			}
		while ( $loop->have_posts() ) : $loop->the_post(); 
			$title = the_title_attribute( 'echo=0' );
			$id = get_the_ID();
			$cat = "";
			$terms = get_the_terms( $post->ID , 'myteam-categories' );
			if(is_array($terms)) {
				foreach ( $terms as $term ) {
				$cat .= 'mt-'.$term->slug.' ';
				}
			}	
			$html .="<div class='myteam-box ".$boxstyle." ".$cat."' >";	
			$html .="<div class='myteam-inner-box ".$innerboxstyle."'>";	
			$lmyteam_options 	= get_option('myteam-settings');
			$dwidth 			= $lmyteam_options['myteam_thumb_width'];	
			if ( has_post_thumbnail() && in_array('photo',$display)) {  
				if(is_array($imgwidth)) {
					$thumbsize = $imgwidth;
			}
			$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), $thumbsize ); 	
			$width = $image[1];	

			if($options['myteam_single_page']=="true" && $url =="active") {
				$html .= "<div class='myteam-box-photo ".$imgstyle."'><a href='".get_permalink($post->ID)."'><img src='".$image[0]."' width='".$width."' title='".$title."' /></a></div>";
			} else {
				$html .= "<div class='myteam-box-photo ".$imgstyle."'><img src='".$image[0]."' width='".$width."' title='".$title."' /></div>";
			}
			} else {
				if ( !has_post_thumbnail() && in_array('photo',$display)) {  
					if($options['myteam_single_page']=="true" && $url =="active") {
						$html .= "<div class='myteam-box-photo ".$imgstyle."'><a href='".get_permalink($post->ID)."'><img src='".plugins_url( '/img/default.png', __FILE__ )."' width='".$dwidth."' title='".$title."' /></a></div>";
					} else {
						$html .= "<div class='myteam-box-photo ".$imgstyle."'><img src='".plugins_url( '/img/default.png', __FILE__ )."' width='".$dwidth."' title='".$title."' /></div>";
					}
				}				
			}
				
			$html .= "<div class='myteam-box-info ".$infostyle." ".$txtstyle." '>";
			$display_array = array();
			$display_array['name']="";		
			if (in_array('name',$display)) : 	
			if($options['myteam_single_page']=="true" && $url =="active") {
				$display_array['name'] ='<div class="myteam-box-title"><a href="'.get_permalink($post->ID).'">'.$title.'</a></div>';
			} else {
				$display_array['name'] = "<div class='myteam-box-title'>".$title."</div>";
			}
			endif;
			
			$display_array['social']  = "";
			if ($socialshow) : 		
			$display_array['social']  = "<div class='myteam-box-social'>".myteam_get_social(get_the_ID(),$socialshow)."</div>";
			endif;
			$display_array['details'] = "";
			$display_array['details'] = "<div class='myteam-box-details'>".myteam_get_information(get_the_ID(),true,$display,false)."</div>";
			
			global $myteam_display_order;
			foreach($myteam_display_order as $disp) {
				$html .= $display_array[$disp];
			}
			$html .="</div>";
			$html .="</div>";
			$html .="</div>";
		endwhile; 
		$html .="</div>";
	}
	
	if($layout=='hover') {
		$imgstyle = myteam_get_img_style($style);
		$txtstyle = myteam_get_txt_style($style);
		$boxstyle = myteam_get_box_style($style);
		$infostyle = myteam_get_info_style($style);	
		$wrapstyle = myteam_get_wrap_style($style);	
		$theme = myteam_get_themes($style,'hover');	
		myteam_add_theme($theme,'hover');
		$wrapid = "myteam-hover-wrap";
		if($theme!="default") { $wrapid = "myteam-".$theme."-wrap"; }
		$html .="<div id='".$wrapid."'>";	
	
		if (in_array('filter',$display) || in_array('enhance-filter',$display) ) {
		if (in_array('filter',$display)) {
			myteam_filter_code();
			$html .= "<ul id='mt-filter-nav'>";
		}
		if (in_array('enhance-filter',$display)) {
			myteam_enhance_filter_code();
			$html .= "<ul id='mt-enhance-filter-nav'>";
		}
		$html .= "<li id='mt-all'>".$myteam_labels['filter']['all-entries-label']."</li>";
		$terms = get_terms("myteam-categories");
		$count = count($terms);
		if ( $count > 0 ){		 
		 foreach ( $terms as $term ) {
			$html .= "<li id='mt-".$term->slug."'>".$term->name."</li>";
			 }		 
		}
		$html .= "</ul>";
		$boxstyle .=" myteam-filter-active";
		}
			$lmyteam_options = get_option('myteam-settings');
			$dwidth = $lmyteam_options['myteam_thumb_width'];	
		if(is_array($imgwidth)) {
			$thumbsize = $imgwidth;
			$dwidth = $thumbsize[0];
		}
		
		while ( $loop->have_posts() ) : $loop->the_post(); 
		$title = the_title_attribute( 'echo=0' );
		$id = get_the_ID();
		$cat = "";
		$terms = get_the_terms( $post->ID , 'myteam-categories' );
		if(is_array($terms)) {
			foreach ( $terms as $term ) {
				$cat .= 'mt-'.$term->slug.' ';
			}
		}
		$html .='<div class="myteam-hover-box '.$boxstyle.' '.$cat.'"><div style="margin-left:auto; margin-right:auto; width:'.$dwidth.'px;">';
		$html .='<span class="'.$imgstyle.'">';
		if ( has_post_thumbnail()) :
			$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), $thumbsize ); 	
			$width = $image[1];	
			$html .="<img src='".$image[0]."' width='".$width."'/>";
		endif;
		if ( !has_post_thumbnail()) {  
			$html .="<img src='".plugins_url( '/img/default.png', __FILE__ )."' width='".$dwidth."'/>";
		}
		$html .='<span class="myteam-hover-info">';
		$html .="<div class='myteam-box-info ".$txtstyle."'><div class='myteam-box-info-inner'>";
		if (in_array('name',$display)) : 	
			if($options['myteam_single_page']=="true" && $url =="active") {
				$html .='<div class="myteam-box-title"><a href="'.get_permalink($post->ID).'">'.$title.'</a></div>';
			} else {
				$html .="<div class='myteam-box-title'>".$title."</div>";
			}
		endif;
		if ($socialshow) : 		
			$html .="<div class='myteam-box-social'>".myteam_get_social(get_the_ID(),$socialshow)."</div>";
		endif;
		$html .="<div class='myteam-box-details'>".myteam_get_information(get_the_ID(),true,$display,false)."</div></div></div></span></span></div></div>";
		endwhile; 
		$html .="</div>";
	}
	wp_reset_postdata();
	$html = "<div class='myteam'>".$html."</div>";
	return $html;
}

function myteam_build_table_layout($loop,$url,$display,$style) {
	$theme 		= myteam_get_themes($style,'table');	
	myteam_add_theme($theme,'table');	
	$html 		= "";
	$options 	= get_option('myteam-settings');
	$imgstyle 	= myteam_get_img_style($style);
	$txtstyle 	= myteam_get_txt_style($style);
	$wrapstyle 	= myteam_get_wrap_style($style);
	$html .= "<table class='myteam-box-table ".$wrapstyle."'>";
	while ( $loop->have_posts() ) : $loop->the_post(); 
		$title  	= the_title_attribute( 'echo=0' );
		$id 		= get_the_ID();
		$smallicons = in_array('smallicons',$display);

		if($smallicons) {
			$iconposition 	= '<i class="icon-chevron-sign-right"></i> ';
			$iconemail 		= '<i class="icon-envelope-alt"></i> ';
			$icontel 		= '<i class="icon-phone-sign"></i> ';
			$iconhtml 		= '<i class="icon-align-justify"></i> ';
			$iconpersonal 	= '<i class="icon-external-link"></i> ';
			$iconlocation 	= '&nbsp;<i class="icon-map-marker"></i>&nbsp;';
		} else {
			$iconposition 	= '';
			$iconemail 		= '';
			$icontel 		= '';
			$iconhtml 		= '';
			$iconpersonal 	= '';
			$iconlocation 	= '';	
		}
	$html .="<tr class='".$txtstyle."'>";
		if(in_array('photo',$display)){
			$width 	= $options['myteam_timg_width'];
			$height = $options['myteam_timg_height'];
			$html 	.='<td><div class="'.$imgstyle.'">';
				if ( has_post_thumbnail() ) {
					$thumb = wp_get_attachment_image_src(get_post_thumbnail_id($id),array($width,$height));	
					if($options['myteam_single_page']=="true" && $url =="active") {
						$html .='<a href="'.get_permalink($id).'"><img src="'.$thumb[0].'" width="'.$thumb[1].'" /></a>';
					} else {
						$html .='<img src="'.$thumb[0].'" width="'.$thumb[1].'" />';
					}
				}
			
				if ( !has_post_thumbnail()) {  
					if($options['myteam_single_page']=="true" && $url =="active") {
						$html .='<a href="'.get_permalink($id).'"><img src="'.plugins_url( '/img/default.png', __FILE__ ).'" width="'.$width.'" /></a>';
					} else {
						$html .='<img src="'.plugins_url( '/img/default.png', __FILE__ ).'" width="'.$width.'" />';
					}
				}
			$html .= '</div></td>';
		}
		
		if(in_array('name',$display)){
			if($options['myteam_single_page']=="true" && $url =="active") {
				$html .='<td><a href="'.get_permalink($id).'">'.$title.'</a></td>';
			} else {
				$html .="<td>".$title."</td>";
			}	
		}
	
		if(in_array('position',$display)) {
			$tsposition = get_post_meta($id,'_mtposition',true);
			$html .= "<td>";
				if($tsposition!="") { 	$html .= $iconposition.$tsposition; }
			$html .= "</td>";
		}
	
		if(in_array('email',$display)){
			$tsemail = htmlentities ( get_post_meta($id,'_mtemail',true) );
			$html .= "<td>";
			if(($tsemail!="")) { 
				$mailto = isset($options['myteam_mailto']);
				if($mailto): $tsemail = "<a href='mailto:".$tsemail."'>".$tsemail."</a>"; endif;
				$html .= $iconemail.$tsemail;
			}
			$html .= "</td>";
		}
	
		if(in_array('telephone',$display)){
			$tstel = get_post_meta($id,'_mttel',true);
			$html .= "<td>";
		if(($tstel!="")) { $html .= $icontel.$tstel; }
			$html .= "</td>";
		}
		
		if(in_array('location',$display)){
			$tsloc = get_post_meta($id,'_mtlocation',true);
			$html .= "<td>";
		if(($tsloc!="")) { $html .= $iconlocation.$tsloc; }
			$html .= "</td>";
		}
	
		if(in_array('twitusername',$display)){
			$tstwitterusername = get_post_meta($id,'_mttwitusername',true);
			$html .= "<td>";
		if(($tstwitterusername!="")) { $html .= $iconhtml.$tstwitterusername; }
			$html .= "</td>";
		}
	
		if(in_array('social',$display)){
			$social = myteam_get_social($id,true);
			$html .= "<td><div class='myteam-box-social'>".$social."</div></td>";
		}
	
	
		if(in_array('website',$display)){
			$tsweb = get_post_meta($id,'_mtpersonal',true);
			$tswebstrip = myteam_strip_http($tsweb);
			$html .= "<td>";
		if(($tsweb!="")) { $html .= $iconpersonal."<a href='".$tsweb."' target='_blank'>".$tswebstrip."</a>";}
			$html .= "</td>";
		}
		$html .= "</tr>";
	endwhile;
	$html .= "</table>";
	return $html; 
}

function myteam_add_theme($theme,$layout) {
	global $myteam_theme_names;
	$thadd = $myteam_theme_names[$layout][$theme];
	wp_deregister_style( $thadd['name']);
	wp_register_style($thadd['name'], plugins_url($thadd['link'], __FILE__ ),array(),false,false);
	wp_enqueue_style($thadd['name'] );			
}

function myteam_default_layout() {
	wp_deregister_style( 'myteam-normal-style' );
	wp_register_style( 'myteam-normal-style', plugins_url( 'css/normal.css', __FILE__ ),array(),false,false);
	wp_enqueue_style( 'myteam-normal-style' );			
}

function myteam_pager_layout() {
	wp_deregister_script( 'myteam-bxslider' );
	wp_register_script( 'myteam-bxslider', plugins_url( 'js/bxslider/jquery.bxslider.js', __FILE__ ),array('jquery'),false,false);
	wp_enqueue_script( 'myteam-bxslider' );			
	add_action( 'wp_print_footer_scripts', 'myteam_pager_code' );	
}

function myteam_filter_code() {
	wp_deregister_script( 'myteam-filter' );
	wp_register_script( 'myteam-filter', plugins_url( '/js/filter.js', __FILE__ ),array('jquery'),false,false);
	wp_enqueue_script( 'myteam-filter' );
}

function myteam_enhance_filter_code() {
	wp_deregister_script( 'myteam-enhance-filter' );
	wp_register_script( 'myteam-enhance-filter', plugins_url( '/js/filter-enhance.js', __FILE__ ),array('jquery'),false,false);
	wp_enqueue_script( 'myteam-enhance-filter' );
}

function myteam_add_global_css() {
	wp_deregister_style( 'myteam-global-style' );
	wp_register_style( 'myteam-global-style', plugins_url( '/css/global.css', __FILE__ ),array(),false,false);
	wp_enqueue_style( 'myteam-global-style' );	
} 
	

function myteam_add_smallicons_css() {
	wp_deregister_style( 'myteam-smallicons' );
	wp_register_style( 'myteam-smallicons', plugins_url( '/css/font-awesome/css/font-awesome.min.css', __FILE__ ),array(),false,false);
	wp_enqueue_style( 'myteam-smallicons' );	
} 

	
function myteam_get_image($id) {
	$html = "";	
	$options = get_option('myteam-settings');
	if(isset($options['myteam_single_show_photo']) && has_post_thumbnail($id)) { 
		$image = wp_get_attachment_image_src( get_post_thumbnail_id( $id ), 'myteam-thumb' ); 
		$html .=   '<div><img src="'.$image[0].'" ></div>';
	}
	return $html;	
}

function myteam_get_twitter($id) {
	$options = get_option('myteam-settings');
	$tstwitter = get_post_meta( $id, '_mttwitter', true );
	$html ="";
	if(isset($options['myteam_single_show_twitter']) && ($tstwitter!="")) { 
		$title = "Latest Tweets";
		if(isset($options['myteam_twitter_title'])) {
			$title = $options['myteam_twitter_title'];
		}	
		$html .=   "<h3>".$title."</h3>";
		$html .= '';
	}
	return $html;
	
}

function myteam_get_information($id,$show,$display=array(),$singular=false) {
	$options 		= get_option('myteam-settings');
	$html="";
	$position 		= in_array('position',$display);
	$email 			= in_array('email',$display);
	$tel 			= in_array('telephone',$display);
	$twitusername 	= in_array('twitusername',$display);
	$website 		= in_array('website',$display);
	$location 		= in_array('location',$display);
	$smallicons 	= in_array('smallicons',$display);
	if($singular) {
		$position 		= isset($options['myteam_single_show_position']);
		$email			= isset($options['myteam_single_show_email']);
		$tel 			= isset($options['myteam_single_show_telephone']);
		$twitusername 	= isset($options['myteam_single_show_twitusername']);
		$website 		= isset($options['myteam_single_show_website']);
		$location 		= isset($options['myteam_single_show_location']);
		$smallicons 	= isset($options['myteam_single_show_smallicons']);
		if($smallicons) {
			myteam_add_smallicons_css();
		}
	}
	$tsposition 		= get_post_meta( $id, '_mtposition', true );
	$tsemail 			= htmlentities ( get_post_meta( $id, '_mtemail', true ) );
	$tstel 				= get_post_meta( $id, '_mttel', true );
	$tstwitterusername 	= get_post_meta( $id, '_mttwitusername', true );
	$tspersonal 		= get_post_meta( $id, '_mtpersonal', true );
	$tslocation 		= get_post_meta( $id, '_mtlocation', true );

	global $myteam_small_icons;
	if($smallicons) {
		$iconposition 	= $myteam_small_icons['position'];
		$iconemail 		= $myteam_small_icons['email'];
		$icontel 		= $myteam_small_icons['telephone'];
		$iconhtml 		= $myteam_small_icons['html'];
		$iconpersonal 	= $myteam_small_icons['website'];
		$iconlocation 	= $myteam_small_icons['location'];
	} else {
		$iconposition 	= '';
		$iconemail 		= '';
		$icontel 		= '';
		$iconhtml 		= '';
		$iconpersonal 	= '';
		$iconlocation 	= '';	
	}
	
	$info_array = array();

	if(($position)&& ($tsposition!="")) { 
		$info_array['position'] =    "<div class='myteam-single-position'>".$iconposition.$tsposition."</div>"; 
	}		
	if(($email) && ($tsemail!="")) { 
		$mailto = isset($options['myteam_mailto']);
		if($mailto): $tsemail = "<a href='mailto:".$tsemail."'>".$tsemail."</a>"; endif;		
		$info_array['email'] =   "<div class='myteam-single-email'>".$iconemail.$tsemail."</div>";
	}
	if(($tel) && ($tstel!="")) { 
		$info_array['telephone'] = "<div class='myteam-single-telephone'>".$icontel.$tstel."</div>";
	}
	if(($location) && ($tslocation!="")) { 
		$info_array['location'] =   "<div class='myteam-single-location'>".$iconlocation.$tslocation."</div>";
	}
	if(($twitusername) && ($tstwitterusername!="")){ 
		$info_array['html'] =  "<div class='myteam-single-twitusername'>". $iconhtml.$tstwitterusername."</div>";
	}
	if(($website) && ($tspersonal!="")) { 
		$tspersonalt = myteam_strip_http($tspersonal);
		$info_array['website'] =   "<div class='myteam-single-website'>".$iconpersonal."<a href='".$tspersonal."' target='_blank'>".$tspersonalt."</a></div>";  		
	}
	
	global $myteam_content_order;
	
	foreach ($myteam_content_order as $info) {
		if(isset($info_array[$info])) {
			$html.=$info_array[$info];
		}
	}
	return $html;
}

function myteam_get_social($id,$show) {

		$html="";
		global $myteam_social_order;
		if($show) {
		$options 		= get_option('myteam-settings');	
		//$twitusername 	= in_array('twitusername',$display);
		
		
					
		$tslinkedin 	= get_post_meta( $id, '_mtlinkedin', true );
		$tsfacebook 	= get_post_meta( $id, '_mtfacebook', true );
		$tstwitter 		= get_post_meta( $id, '_mttwitter', true );
		$tsgplus 		= get_post_meta( $id, '_mtgplus', true );
		$tspinterest 	= get_post_meta( $id, '_mtpinterest', true );
		$tsyoutube 		= get_post_meta( $id, '_mtyoutube', true );
		$tsvimeo 		= get_post_meta( $id, '_mtvimeo', true );
		$tsdribbble 	= get_post_meta( $id, '_mtdribbble', true );
		$tsinstagram 	= get_post_meta( $id, '_mtinstagram', true );
		$tsemailico 	= get_post_meta( $id, '_mtemailico', true );
		$twitusername 	= get_post_meta( $id, '_mttwitusername', true );
		
		$folder 		= $options['myteam_single_social_icons'];
		$social_array=array();
		
		
	//if ( true == $args['display_twitter'] && '' != $post->twitter && apply_filters( 'woothemes_our_team_member_twitter', true ) ) {
						
		//			}

		
		if($tslinkedin!=""){ 
			$social_array['linkedin'] 	="<a href='".$tslinkedin."' target='_blank'><img src='".plugins_url( '/img/social/'.$folder.'/linkedin.png', __FILE__ )."'></a>"; 
		}
		
		if($tsfacebook!=""){ 
			$social_array['facebook'] 	="<a href='".$tsfacebook."' target='_blank'><img src='".plugins_url( '/img/social/'.$folder.'/facebook.png', __FILE__ )."'></a>"; 
		}
		
		if($tstwitter!=""){ 
			$social_array['twitter'] 	="<a href='".$tstwitter."' target='_blank'><img src='".plugins_url( '/img/social/'.$folder.'/twitter.png', __FILE__ )."'></a>"; 
		}
		
		if($tsgplus!=""){ 
			$social_array['gplus']	 	="<a href='".$tsgplus."' target='_blank'><img src='".plugins_url( '/img/social/'.$folder.'/gplus.png', __FILE__ )."'></a>";
		}
		
		if($tspinterest!=""){ 
			$social_array['pinterest']  ="<a href='".$tspinterest."' target='_blank'><img src='".plugins_url( '/img/social/'.$folder.'/pinterest.png', __FILE__ )."'></a>"; 
		}
		
		if($tsyoutube!=""){ 
			$social_array['youtube'] 	="<a href='".$tsyoutube."' target='_blank'><img src='".plugins_url( '/img/social/'.$folder.'/youtube.png', __FILE__ )."'></a>"; 
		}
		
		if($tsvimeo!=""){ 
			$social_array['vimeo'] 		="<a href='".$tsvimeo."' target='_blank'><img src='".plugins_url( '/img/social/'.$folder.'/vimeo.png', __FILE__ )."'></a>"; 
		}
		
		if($tsdribbble!=""){ 
			$social_array['dribbble'] 	="<a href='".$tsdribbble."' target='_blank'><img src='".plugins_url( '/img/social/'.$folder.'/dribbble.png', __FILE__ )."'></a>"; 
			}
		if($tsinstagram!=""){ 
			$social_array['instagram'] 	="<a href='".$tsinstagram."' target='_blank'><img src='".plugins_url( '/img/social/'.$folder.'/instagram.png', __FILE__ )."'></a>"; 
		}
		if($tsemailico!=""){ 
			$social_array['email'] 		="<a href='mailto:".$tsemailico."' target='_blank'><img src='".plugins_url( '/img/social/'.$folder.'/email.png', __FILE__ )."'></a>"; 
		}
	}
	
	foreach ($myteam_social_order as $info) {
			if(isset($social_array[$info])) {
				$html.=$social_array[$info];
			}
		}
		
		$member_fields = '<div class="our-team-twitter" itemprop="contactPoint"><a href="//twitter.com/' . esc_html( $twitusername ) . '" class="twitter-follow-button" data-show-count="false">Follow @' . esc_html( $twitusername ) . '</a><script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?"http":"https";if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document, "script", "twitter-wjs");</script></div>'  . "\n";		
		
	return $html.$member_fields;
}

function myteam_latest_posts($id) {
	$options = get_option('myteam-settings');
	$html ="";
	$tsuser = get_post_meta( $id, '_mtuser', true );

	if(isset($options['myteam_single_show_posts'])) {
	
		if($tsuser!="0") {	
			$args = array(
				'post_type' => 'post',
				'post_status' => 'publish',
				'author' => $tsuser
			);
		$myteam_posts_query = new WP_Query($args);		
		if($myteam_posts_query->have_posts()) {
			$title = "Latest Posts";
			if(isset($options['myteam_latest_title'])) {
				$title = $options['myteam_latest_title'];
			}	
			$html .=   "<h3>".$title."</h3>";
			$html .=   "<ul>";
			while ( $myteam_posts_query->have_posts() ) : $myteam_posts_query->the_post();
				$html .=   '<li><a href="'.get_permalink().'">' . get_the_title() . '</a></li>';
			endwhile;
			$html .=   "</ul>";
		}
		wp_reset_postdata();
		}
	}
 return $html;
}

function myteam_columns_head($defaults) {
	global $post;
    if ($post->post_type == 'myteam') {
		$defaults['featured_image'] = 'Image';
		$defaults['order'] = '<a href="&orderby=menu_order"><span>Order</span><span class="sorting-indicator"></span></a>';  //if we want the order to display
	}
	return $defaults;
}

function myteam_admin_order($wp_query) {
  if (is_post_type_archive( 'myteam' ) && is_admin() ) {    
		if (!isset($_GET['orderby'])) {
		  $wp_query->set('orderby', 'menu_order');
		  $wp_query->set('order', 'ASC');
		}
	}
}

function myteam_info_metabox() {
	global $post;	
	global $myteam_labels;
	$tsposition 	= get_post_meta( $post->ID, '_mtposition', true );
	$tsemail 		= get_post_meta( $post->ID, '_mtemail', true );
	$tstel 			= get_post_meta( $post->ID, '_mttel', true );
	$tsuser 		= get_post_meta( $post->ID, '_mtuser', true );
	$tstwitusername = get_post_meta( $post->ID, '_mttwitusername', true );
	$tspersonal 	= get_post_meta( $post->ID, '_mtpersonal', true );
	$tslocation 	= get_post_meta( $post->ID, '_mtlocation', true );
?>

<table cellpadding="2" cellspacing="" border="0">
  <tr>
    <td align="right"><label for="_mtposition"><?php echo $myteam_labels['position']['label'] ?>:<br>
      </label></td>
    <td><input id="_mtposition" size="37" name="_mtposition" type="text" value="<?php if( $tsposition ) { echo $tsposition; } ?>" /></td>
    <td><p class="howto"><?php echo $myteam_labels['position']['description'] ?></p></td>
  </tr>
  <tr>
    <td align="right"><label for="_mtemail"><?php echo $myteam_labels['email']['label'] ?>:<br>
      </label></td>
    <td><input id="_mtemail" size="37" name="_mtemail" type="email" value="<?php if( $tsemail ) { echo $tsemail; } ?>" /></td>
    <td><p class="howto"><?php echo $myteam_labels['email']['description'] ?></p></td>
  </tr>
  <tr>
    <td align="right"><?php echo $myteam_labels['location']['label'] ?>:</td>
    <td><input id="_mtlocation" size="37" name="_mtlocation" type="text" value="<?php if( $tslocation ) { echo $tslocation; } ?>" /></td>
    <td><p class="howto"><?php echo $myteam_labels['location']['description'] ?></p></td>
  </tr>
  <tr>
    <td align="right"><?php echo $myteam_labels['telephone']['label'] ?>:</td>
    <td><input id="_mttel" size="37" name="_mttel" type="text" value="<?php if( $tstel ) { echo $tstel; } ?>" /></td>
    <td><p class="howto"><?php echo $myteam_labels['telephone']['description'] ?></p></td>
  </tr>
  <tr>
    <td align="right" nowrap><?php echo $myteam_labels['user']['label'] ?>:</td>
    <td><select name="_mtuser" id="_mtuser">
        <option value="0">No User Associated</option>
        <?php $blogusers = get_users();
    		foreach ($blogusers as $user) { ?>
        <option value="<?php echo $user->ID; ?>" <?php selected( $tsuser, $user->ID ) ?>><?php echo $user->display_name; ?></option>
        <?php } ?>
      </select></td>
    <td><p class="howto"><?php echo $myteam_labels['user']['description'] ?> </p></td>
  </tr>
  <tr>
    <td align="right"><p>
        <label for="_mtpersonal"><?php echo $myteam_labels['website']['label'] ?>:<br>
        </label>
      </p></td>
    <td><input id="_mtpersonal" size="37" name="_mtpersonal" type="url" value="<?php if( $tspersonal ) { echo $tspersonal; } ?>" /></td>
    <td><p class="howto"><?php echo $myteam_labels['website']['description'] ?></p></td>
  </tr>
  <tr>
    <td align="right"><p>
        <label for="_mtpersonal"><?php echo $myteam_labels['twittername']['label'] ?>:<br>
        </label>
      </p></td>
    <td><input id="_mttwitusername" size="37" name="_mttwitusername" value="<?php if( $tstwitusername ) { echo $tstwitusername; } ?>" /></td>
    <td><p class="howto"><?php echo $myteam_labels['twittername']['description'] ?></p></td>
  </tr>
</table>
<?php } 

function myteam_social_metabox() {
	global $post;	
	global $myteam_labels;
	
	$helptext 		= $myteam_labels['help']['social'];
	$tslinkedin 	= get_post_meta( $post->ID, '_mtlinkedin', true );
	$tsfacebook 	= get_post_meta( $post->ID, '_mtfacebook', true );
	$tstwitter 		= get_post_meta( $post->ID, '_mttwitter', true );
	$tsgplus 		= get_post_meta( $post->ID, '_mtgplus', true );	
	$tsyoutube 		= get_post_meta( $post->ID, '_mtyoutube', true );
	$tsvimeo 		= get_post_meta( $post->ID, '_mtvimeo', true );
	$tsinstagram 	= get_post_meta( $post->ID, '_mtinstagram', true );
	$tsemailico 	= htmlentities ( get_post_meta( $post->ID, '_mtemailico', true ) );
	$tsdribbble 	= get_post_meta( $post->ID, '_mtdribbble', true );
	$tspinterest 	= get_post_meta( $post->ID, '_mtpinterest', true );
?>
<p class="howto"><?php echo $helptext; ?></p>
<table cellpadding="2" cellspacing="" border="0" bgcolor="#CCCCCC" width="100%">
  <tr>
    <td align="right"><p>
        <label for="tslinkedin"><img src="<?php echo get_option('home'); ?>/wp-content/plugins/wp-my-team/img/linkedin.png" alt="linkedin" align="absbottom" /><br>
        </label>
      </p></td>
    <td><input id="_mtlinkedin" size="37" name="_mtlinkedin" type="url" value="<?php if( $tslinkedin ) { echo $tslinkedin; } ?>" placeholder="Linkedin"/></td>
    <td align="right">&nbsp;</td>
    <td align="right"><img src="<?php echo get_option('home'); ?>/wp-content/plugins/wp-my-team/img/vimeo.png" alt="vimeo" align="absbottom" /></td>
    <td><input id="_mtvimeo" size="37" name="_mtvimeo" type="url" value="<?php if( $tsvimeo ) { echo $tsvimeo; } ?>" placeholder="Vimeo" /></td>
  </tr>
  <tr>
    <td align="right"><p>
        <label for="_mtfacebook"><img src="<?php echo get_option('home'); ?>/wp-content/plugins/wp-my-team/img/facebook.png" alt="facebook" align="absbottom" /><br>
        </label>
      </p></td>
    <td><input id="_mtfacebook" size="37" name="_mtfacebook" type="url" value="<?php if( $tsfacebook ) { echo $tsfacebook; } ?>" placeholder="Facebook" /></td>
    <td align="right">&nbsp;</td>
    <td align="right"><img src="<?php echo get_option('home'); ?>/wp-content/plugins/wp-my-team/img/youtube.png" alt="youtube" align="absbottom" /></td>
    <td><input id="_mtyoutube" size="37" name="_mtyoutube" type="url" value="<?php if( $tsyoutube ) { echo $tsyoutube; } ?>" placeholder="Youtube" /></td>
  </tr>
  <tr>
    <td align="right"><p>
        <label for="_mttwitter"><img src="<?php echo get_option('home'); ?>/wp-content/plugins/wp-my-team/img/twitter.png" alt="twitter" align="absbottom" /><br>
        </label>
      </p></td>
    <td><input id="_mttwitter" size="37" name="_mttwitter" type="url" value="<?php if( $tstwitter ) { echo $tstwitter; } ?>" placeholder="Twitter" /></td>
    <td align="right">&nbsp;</td>
    <td align="right"><img src="<?php echo get_option('home'); ?>/wp-content/plugins/wp-my-team/img/email.png" alt="email" align="absbottom" /></td>
    <td><input id="_mtemailico" size="37" name="_mtemailico" type="text" value="<?php if( $tsemailico ) { echo $tsemailico; } ?>" placeholder="Email" />
      <span class="howto">Icon with mailto will be displayed</span></td>
  </tr>
  <tr>
    <td align="right"><p>
        <label for="_mtgplus"><img src="<?php echo get_option('home'); ?>/wp-content/plugins/wp-my-team/img/gplus.png" alt="Google Plus" align="absbottom" /><br>
        </label>
      </p></td>
    <td><input id="_mtgplus" size="37" name="_mtgplus" type="url" value="<?php if( $tsgplus ) { echo $tsgplus; } ?>" placeholder="Google Plus" /></td>
    <td align="right">&nbsp;</td>
    <td align="right"><img src="<?php echo get_option('home'); ?>/wp-content/plugins/wp-my-team/img/instagram.png" alt="Instagram" align="absbottom" /></td>
    <td><input id="_mtinstagram" size="37" name="_mtinstagram" value="<?php if( $tsinstagram ) { echo $tsinstagram; } ?>" placeholder="Instagram" />
      <input id="_mtpinterest" size="37" name="_mtpinterest" type="hidden" value="<?php if( $tspinterest ) { echo $tspinterest; } ?>" />
      <input id="_mtdribbble" size="37" name="_mtdribbble" type="hidden" value="<?php if( $tsdribbble ) { echo $tsdribbble; } ?>" />
    </td>
  </tr>
</table>
<?php
}

function myteam_strip_http($url) {	
	$url = preg_replace('#^https?://#', '', $url);
    return $url;
}

function myteam_message($msg) { echo '<div id="message" class="updated"><p>'.$msg.'</p></div>'; }

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
	$css 		= "default";
	$styles		= explode(',',$style);
	foreach ($styles as $st) {
		if(array_key_exists($st,$themearray)) {
			$css = $themearray[$st]['key'];
		}
	}
	return $css;
}

function myteam_shortcode_page_add() {
	$menu_slug 			= 'edit.php?post_type=myteam';
	$submenu_page_title = 'Shortcode Generator';
    $submenu_title		= 'Shortcode Generator';
	$capability 		= 'manage_options';
    $submenu_slug 		= 'myteam_shortcode';
    $submenu_function 	= 'myteam_shortcode_page';
    $defaultp 			= add_submenu_page($menu_slug, $submenu_page_title, $submenu_title, $capability, $submenu_slug, $submenu_function);
	add_action($defaultp, 'myteam_enqueue_admin_js');
   }

function myteam_enqueue_admin_js() {

	wp_deregister_script( 'myteam-jquery' );
	wp_register_script( 'myteam-jquery', plugins_url( '/js/jquery-1.7.2.js', __FILE__ ),array('jquery'),false,false);
	wp_enqueue_script( 'myteam-jquery' );	


	wp_deregister_script( 'myteam-bxslider' );
	wp_register_script( 'myteam-bxslider', plugins_url( '/js/bxslider/jquery.bxslider.js', __FILE__ ),array('jquery'),false,false);
	wp_enqueue_script( 'myteam-bxslider' );	
	
	wp_deregister_script( 'myteam-filter' );
	wp_register_script( 'myteam-filter', plugins_url( '/js/filter.js', __FILE__ ),array('jquery'),false,false);
	wp_enqueue_script( 'myteam-filter' );
	
	wp_deregister_script( 'myteam-enhance-filter' );
	wp_register_script( 'myteam-enhance-filter', plugins_url( '/js/filter-enhance.js', __FILE__ ),array('jquery'),false,false);
	wp_enqueue_script( 'myteam-enhance-filter' );
	
	wp_deregister_script('myteamadmin');
	wp_register_script( 'myteamadmin', plugins_url( '/js/phpshortcode-generator.js' , __FILE__ ), array('jquery') );
	wp_enqueue_script( 'myteamadmin' );
	
	wp_localize_script( 'myteamadmin', 'ajax_object',array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
	
	global $myteam_theme_names;	
	foreach ($myteam_theme_names as $themearray) {	
		foreach($themearray as $theme) {		
		wp_deregister_style( $theme['name']);
		wp_register_style($theme['name'], plugins_url($theme['link'], __FILE__ ),array(),false,false);
		wp_enqueue_style($theme['name'] );	
		}
	}
	
	wp_deregister_style( 'myteam-global-style' );
	wp_register_style( 'myteam-global-style', plugins_url( '/css/global.css', __FILE__ ),array(),false,false);
	wp_enqueue_style( 'myteam-global-style' );	
			
	wp_deregister_style( 'myteam-smallicons' );
	wp_register_style( 'myteam-smallicons', plugins_url( '/css/font-awesome/css/font-awesome.min.css', __FILE__ ),array(),false,false);
	wp_enqueue_style( 'myteam-smallicons' );	
}

function myteam_run_preview() {	
	$orderby 	= $_POST['porder'];
	$limit 		= $_POST['plimit'];
	$idsfilter 	= $_POST['pidsfilter'];
	$category 	= $_POST['pcategory'];
	$url 		= $_POST['purl'];
	$layout 	= $_POST['playout'];
	$style 		= $_POST['pstyle'];
	$display 	= $_POST['pdisplay']; 
	$img 		= $_POST['pimg']; 	 
	$html 		= generate_myteam($orderby,$limit,$idsfilter,$category,$url,$layout,$style,$display,$img);
	echo $html;
	die(); 
}

function myteam_shortcode_page() { 
	$options 	= get_option('myteam-settings');	
	$categories = $options['myteam_name_category'];
	global $myteam_labels;
	global $myteam_theme_names;
?>
<h1>Shortcode Generator</h1>
<table cellpadding="5" cellspacing="5">
  <tr>
    <td width="20%" valign="top"><div class="postbox" style="width:800px;">
        <form id="shortcode_generator" style="padding:20px;">
          <h2>What entries do you want to display:</h2>
          <table width="800" border="0">
            <tr>
              <td valign="top"><p>
                  <label for="category"><?php echo $categories; ?>:</label>
                  <select id="category" name="category" onChange="myteamshortcodegenerate()">
                    <option value="0">All</option>
                    <option value="0">sss</option>
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
                </p></td>
              <td valign="top"><p>
                  <label for="orderby">Order By:</label>
                  <select id="orderby" name="orderby" onChange="myteamshortcodegenerate()">
                    <option value="none">Default (Order Field)</option>
                    <option value="title">Name</option>
                    <option value="ID">ID</option>
                    <option value="date">Date</option>
                    <option value="modified">Modified</option>
                    <option value="rand">Random</option>
                  </select>
                </p></td>
              <td valign="top"><p>
                  <label for="limit">Number of entries:</label>
                  <input size="3" id="limit" name="limit" type="text" value="0" onChange="myteamshortcodegenerate()" />
                  <span class="howto" style=" line-height:20px;"> (Leave blank or 0 to display all)</span></p>
                </p></td>
              <td valign="top"><p>
                  <label for="idsfilter">IDs to display:</label>
                  <input size="10" id="idsfilter" name="idsfilter" type="text" value="0" onChange="myteamshortcodegenerate()" />
                  <span class="howto" style=" line-height:20px;"> (Comma sperated ID values to display.<br />
                  Example: 7,11)</span></p>
                </p></td>
            </tr>
          </table>
          <h2>What information do you want to display:</h2>
          <div style="border:1px solid #ccc; background:#FFF; padding:10px;">
            <table width="100%" border="0" cellspacing="5" cellpadding="0">
              <tr>
                <td><input name="name" type="checkbox" id="name" onChange="myteamshortcodegenerate()" value="name" checked>
                  <label for="name"><?php echo $myteam_labels['name']['label']; ?></label></td>
                <td><input name="photo" type="checkbox" id="photo" onChange="myteamshortcodegenerate()" value="photo" checked>
                  <label for="photo"><?php echo $myteam_labels['photo']['label']; ?></label></td>
                <td><input name="smallicons" type="checkbox" id="smallicons" value="smallicons" onChange="myteamshortcodegenerate()">
                  <label for="smallicons"><?php echo $myteam_labels['smallicons']['label']; ?></label>
                  &nbsp;</td>
                <td><input name="social" type="checkbox" id="social" onChange="myteamshortcodegenerate()" value="social" checked>
                  <label for="social"><?php echo $myteam_labels['socialicons']['label']; ?></label></td>
                <td><input name="social" type="checkbox" id="social" onChange="myteamshortcodegenerate()" value="social" checked>
                  <label for="social"><?php echo $myteam_labels['socialicons']['label']; ?></label></td>
              </tr>
              <tr>
                <td><label for="position">
                  <input name="position" type="checkbox" id="position" onChange="myteamshortcodegenerate()" value="position" checked>
                  <?php echo $myteam_labels['position']['label']; ?></label></td>
                <td><label for="location">
                  <input name="location" type="checkbox" id="location" value="location" onChange="myteamshortcodegenerate()">
                  <?php echo $myteam_labels['location']['label']; ?> &nbsp;</label></td>
                <td><input name="email" type="checkbox" id="email" onChange="myteamshortcodegenerate()" value="email" checked>
                  <label for="email"><?php echo $myteam_labels['email']['label']; ?></label>
                  &nbsp;</td>
                <td><input name="website" type="checkbox" id="website" value="website" onChange="myteamshortcodegenerate()">
                  <label for="website"><?php echo $myteam_labels['website']['label']; ?></label>
                  &nbsp;</td>
                <td><input name="telephone" type="checkbox" id="telephone" value="telephone" onChange="myteamshortcodegenerate()">
                  <label for="telephone"><?php echo $myteam_labels['telephone']['label']; ?> </label></td>
              </tr>
            </table>
          </div>
          <div style="border:1px solid #ccc; background:#FFF; padding:10px; margin:10px 0px 0px;">
            <p>
              <label for="singleurl">Single Page Link: </label>
              <select id="singleurl" name="singleurl" onChange="myteamshortcodegenerate()">
			   <option value="active">Active</option>
                <option value="inactive">Inactive</option>
               
              </select>
              <span class="howto" style=" line-height:20px;">Only considered if Single Page is Active on Settings</span></p>
            <h2>How you want it to look like:</h2>
            <div style="border:1px solid #ccc; background:#FFF; padding:10px; margin:10px 0px 0px;"> Load a Layout Preset: <br>
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
          </div>
          <div id="griddiv" style="margin:10px 0px 0px;">
            <div style="border:1px solid #ccc; background:#FFF; padding:5px;">
              <label for="filtergrid"><?php echo $categories.' '.$myteam_labels['filter']['label']; ?>:</label>
              <select name="filtergrid" id="filtergrid" onChange="myteamshortcodegenerate()">
                <option value="inactive" selected>Inactive</option>
                <option value="filter">Active - Hide Filter</option>
                <option value="enhance-filter">Active - Enhance Filter</option>
              </select>
              <span class="howto" style=" line-height:20px;">When active, a jQuery Category filter will display above the Grid. Only works when all categories are displaying. </span>
              <p>Theme:
                <label for="grid-styling"></label>
                <select name="grid-styling" id="grid-styling" onChange="myteamshortcodegenerate()">
                  <?php foreach ($myteam_theme_names['grid'] as $tbstyle) { ?>
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
            </div>
          </div>
          <div style="border:1px solid #ccc; background:#FFF; padding:5px; margin:10px 0px 0px;">
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

//admin enque scrips
function myteam_enqueue_settings_js() {
	wp_enqueue_script( 'jquery-ui-tabs' );
	wp_deregister_style( 'myteam-settings-style' );
	wp_register_style( 'myteam-settings-style', plugins_url( 'css/settings.css', __FILE__ ),array(),false,false);
	wp_enqueue_style( 'myteam-settings-style' );		
}

function myteam_settings_page () { 
	global $myteam_labels;
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
					<option value="vcard" <?php selected($options['myteam_single_page_style'], 'vcard' ); ?>>Information Card</option>
					<option value="responsive" <?php selected($options['myteam_single_page_style'], 'responsive' ); ?>>Columns</option>
					<option value="none" <?php selected($options['myteam_single_page_style'], 'none' ); ?>>None</option>
            </select></td>
        </tr>
		 <tr>
          <td align="left">Share your team member:</td>
          <td nowrap><select name="myteam-settings[myteam_single_page_share]">
					<option value="1" <?php selected($options['myteam_single_page_share'], '1' ); ?>>Yes</option>
					<option value="0" <?php selected($options['myteam_single_page_share'], '0' ); ?>>No</option>
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
<?php } ?>