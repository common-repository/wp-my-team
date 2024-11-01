<?php
/*
Plugin Name: MyTeam Wordpress
Plugin URI: http://www.tbsin.com/
Description: This plugin allows you to manage the members of your team/staff and display them in multiple ways shortcode.
Author: Selvabalajee
Version: 1.1
Author URI:http://www.tbsin.com/
*/


require_once dirname( __FILE__ ) . '/team-function.php';
require_once dirname( __FILE__ ) . '/team-single.php';
require_once dirname( __FILE__ ) . '/class.sorting.php';


add_action('init', 'register_myteam');
add_action('init', 'myteam_add_thumbnails_img');
add_action('init', 'myteam_build_taxonomies', 0);
add_action('init', 'myteam_add_thumbnails_img');

add_action('admin_init', 'myteam_add_social_metabox');
add_action('admin_init', 'myteam_add_info_metabox');
add_action('admin_init', 'register_myteam_settings');

add_action('admin_menu', 'myteam_shortcode_page_add');
add_action('admin_menu', 'myteam_admin_page');

add_action('save_post', 'myteam_save_info' );
add_action('save_post', 'myteam_save_social');
add_action('manage_posts_custom_column', 'myteam_columns_content', 10, 2);
add_action('wp_ajax_myteam', 'myteam_run_preview');

add_shortcode('my-team', 'shortcode_myteam');


add_filter('widget_text', 'do_shortcode');
add_filter('the_excerpt', 'do_shortcode');
add_filter('pre_get_posts', 'myteam_admin_order');
add_filter('manage_posts_columns', 'myteam_columns_head');

register_activation_hook(__FILE__, 'myteam_defaults');
add_theme_support( 'post-thumbnails', array( 'post', 'myteam' ) ); 

$myteam_change_default_title_en = true;
if($myteam_change_default_title_en) {
	add_filter( 'enter_title_here', 'myteam_change_default_title' );
}

$myteam_display_order = array (
	1 => 'name',
	2 => 'details',
	3 => 'social'	
);

$myteam_content_order = array (
	1 => 'position',
	2 => 'location',
	3 => 'telephone',
	4 => 'email',
	5 => 'html',
	6 => 'website'	
);

$myteam_social_order = array (
	1 => 'linkedin',
	2 => 'facebook',
	3 => 'twitter',
	4 => 'gplus',
	5 => 'youtube',
	6 => 'vimeo',
	7 => 'instagram',
	8 => 'email',
	9 => 'pinterest', //not being used in the current version
);

$myteam_small_icons = array(
	'position'	=> '<i class="icon-chevron-sign-right"></i> ',
	'email' 	=> '<i class="icon-envelope-alt"></i> ',
	'telephone' => '<i class="icon-phone-sign"></i> ',
	'html' 		=> '<i class="icon-align-justify"></i> ',
	'website' 	=> '<i class="icon-external-link"></i> ',
	'location' 	=> '&nbsp;<i class="icon-map-marker"></i>&nbsp;'
);

$myteam_labels = array (
	'titles' => array(
				'info' 		=> 'Team Member Additional Information',
				'social' 	=> 'Social Profile Links'	
				),
	'help' => array(
				'social' 	=> 'Use the complete URL to the profile page. Example: http://www.facebook.com/profile'	
				),
	'position' => array (
				'key' 			=> 'position',
				'meta_name' 	=> '_mtposition',
				'label' 		=> 'Job Title',
				'description' 	=> 'Place your title'
				),
	'email' => array (
				'key' 			=> 'email',
				'meta_name' 	=> '_mtemail',
				'label' 		=> 'Email',
				'description' 	=> 'User contact email.'
				),
	'location' => array (
				'key' 			=> 'location',
				'meta_name' 	=> '_mtlocation',
				'label' 		=> 'Location',
				'description' 	=> 'Location//Address of this member.'
				),
	'telephone' => array (
				'key' 			=> 'telephone',
				'meta_name' 	=> '_mttel',
				'label' 		=> 'Phone',
				'description' 	=> 'Phone Number.'
				),
	'user' => array (
				'key' 			=> 'user',
				'meta_name' 	=> '_mtuser',
				'label' 		=> 'User/Author Profile',
				'description' 	=> 'member is associated with a user account select it here.'
				),
	'website' => array (
				'key' 			=> 'website',
				'meta_name' 	=> '_mtpersonal',
				'label' 		=> ' Website',
				'description' 	=> 'URL to personal.'
				),
	'twittername' => array (
				'key' 			=> 'twitusername',
				'meta_name' 	=> '_mttwitusername',
				'label' 		=> 'Twitter Username',
				'description' 	=> 'Twitter Username'
				),
	'name' => array (
				'key' 			=> 'name',
				'meta_name' 	=> 'title',
				'label' 		=> 'Name/Title',
				'description' 	=> 'Name'
				),
	'photo' => array (
				'key' 			=> 'photo',
				'meta_name' 	=> 'featured_image',
				'label' 		=> 'Photo/Image',
				'description' 	=> 'Featured Image.'
				),
	'smallicons' => array (
				'key' 			=> 'smallicons',
				'label' 		=> 'Small Icons',
				'description' 	=> 'Small CSS Icons.'
				),
	'socialicons' => array (
				'key' 			=> 'socialicons',
				'label' 		=> 'Social Icons',
				'description' 	=> 'Social Icons.'
				),
	'filter' => array (
				'label' 		=> 'Filter',
				'all-entries-label' => 'All',
	)
);

$myteam_wrapstyles 	= array(
	'normal-float' 		=> 'mt-normal-float-wrap',
	'1-columns' 		=> 'mt-responsive-wrap',
	'2-columns' 		=> 'mt-responsive-wrap',
	'3-columns' 		=> 'mt-responsive-wrap',
	'4-columns' 		=> 'mt-responsive-wrap',
	'5-columns' 		=> 'mt-responsive-wrap',
	'6-columns' 		=> 'mt-responsive-wrap',
	'retro-box-theme' 	=> 'mt-retro-style',
	'white-box-theme' 	=> 'mt-white-box-style',
	'card-theme' 		=> 'mt-theme-card-style',
	'odd-colored' 		=> 'mt-table-odd-colored'
	);


$myteam_boxstyles = array(
	'img-left'		=>'mt-float-left',
	'img-right'		=>'mt-float-right',
	'normal-float'  => 'mt-normal-float',
	'1-column' 		=> 'mt-col_1',
	'2-columns' 	=> 'mt-col_2',
	'3-columns' 	=> 'mt-col_3',
	'4-columns' 	=> 'mt-col_4',
	'5-columns' 	=> 'mt-col_5',
	'6-columns' 	=> 'mt-col_6'
	);
	
$myteam_innerboxstyles = array(
	'img-left'		=>'mt-float-left',
	'img-right'		=>'mt-float-right'
	);

$myteam_txtstyles = array(
	'text-left'		=>'mt-align-left',
	'text-right'	=>'mt-align-right',
	'text-center'	=>'mt-align-center'
	);

$myteam_imgstyles = array(
		'img-rounded'	=>'mt-rounded',
		'img-circle'	=>'mt-circle',
		'img-square'	=>'mt-square',
		'img-grayscale' =>'mt-grayscale',
		'img-grayscale-shadow' =>'mt-grayscale-shadow',
		'img-shadow' 	=>'mt-shadow',
		'img-left' 		=>'mt-img-left',
		'img-right' 	=>'mt-img-right',
		'img-white-border' 		=> 'mt-white-border'
		);

$myteam_infostyles = array(
	'img-left'			=>'mt-float-left',
	'img-right'			=>'mt-float-right'
	);
	
$myteam_pagerstyles = array(
	'thumbs-left'		=>'mt-pager-left',
	'thumbs-right'		=>'mt-pager-right',
	'thumbs-below'		=>'mt-pager-below'
	);

$myteam_pagerboxstyles = array(
	'thumbs-left'		=>'mt-pager-box-right',
	'thumbs-right'		=>'mt-pager-box-left',
	'thumbs-below'		=>'mt-pager-box-above'
	);

$myteam_theme_names = array (
		'grid' => array(
		'default' 	=> array (
			'key' 	=> 'default',
			'name' 	=> 'myteam-default-style',
			'link' 	=> 'css/normal.css',
			'label' => 'Default'
			),
		'retro-box-theme' => array (
			'key' 	=> 'retro-box-theme',
			'name' 	=> 'myteam-retro-style',
			'link' 	=> 'css/retro.css',
			'label' => 'Retro boxes'
			
			),
		'white-box-theme' => array (
			'key' 	=> 'white-box-theme',
			'name' 	=> 'myteam-white-box-style',
			'link' 	=> 'css/white-box.css',
			'label' => 'White Box with Shadow'
			),
		'card-theme' => array (
			'key' 	=> 'card-theme',
			'name' 	=> 'myteam-card-theme-style',
			'link' 	=> 'css/theme-card.css',
			'label' => 'Simple Card'
			)
		),
			
		'hover' 	=> array(
		'default' 	=> array (
			'key' 	=> 'default',
			'name' 	=> 'myteam-default-hover-style',
			'link' 	=> 'css/normal-hover.css',
			'label' => 'Default'
			),
		'white-hover' => array (
			'key' 	=> 'white-hover',
			'name' 	=> 'myteam-white-hover-style',
			'link' 	=> 'css/white-hover.css',
			'label' => 'White Hover'
			)
		),	
		'table' 	=> array(
		'default' 	=> array (
			'key' 	=> 'default',
			'name' 	=> 'myteam-default-table-style',
			'link' 	=> 'css/table.css',
			'label' => 'Default'
			),
		'odd-colored' => array (
			'key' 	=> 'odd-colored',
			'name' 	=> 'myteam-odd-colored-table-style',
			'link' 	=> 'css/table-odd-colored.css',
			'label' => 'Odd Rows Colored'
			)
		),	
		'pager' 	=> array(
		'default' 	=> array (
			'key' 	=> 'default',
			'name' 	=> 'myteam-default-pager-style',
			'link' 	=> 'css/pager.css',
			'label' => 'Default'
			)
		)	
	);
?>