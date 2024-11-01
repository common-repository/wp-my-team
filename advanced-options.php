<?php
/*
Author: Selvabalaji
Version: 1.0
Author URI:http://www.tbsin.com/
Packages : Advanced Option
*/


//ORDER OF CONTENT
//MAIN ORDER - name, details, social
$myteam_display_order = array (
	1 => 'name',
	2 => 'details',
	3 => 'social'	
);
//CONTENT ORDER
//position,location,telephone,email,html,website
$myteam_content_order = array (
	1 => 'position',
	2 => 'location',
	3 => 'telephone',
	4 => 'email',
	5 => 'html',
	6 => 'website'	
);

//SOCIAL ICONS ORDER
//linkedin,facebook,twitter,gplus,pinterest,youtube,vimeo,dribbble
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

//ICONS
//see more at http://fortawesome.github.io/Font-Awesome/icons/
$myteam_small_icons = array(
		'position' => '<i class="icon-chevron-sign-right"></i> ',
		'email' => '<i class="icon-envelope-alt"></i> ',
		'telephone' => '<i class="icon-phone-sign"></i> ',
		'html' => '<i class="icon-align-justify"></i> ',
		'website' => '<i class="icon-external-link"></i> ',
		'location' => '&nbsp;<i class="icon-map-marker"></i>&nbsp;'
);


//Labels

$myteam_labels = array (

	'titles' => array(
				'info' => 'Team Member Additional Information',
				'social' => 'Social Profile Links'	
				),
	'help' => array(
				'social' => 'Use the complete URL to the profile page. Example: http://www.facebook.com/profile'	
				),

	'position' => array (
				'key' => 'position',
				'meta_name' => '_mtposition',
				'label' => 'Job Title',
				'description' => 'The job description, position or functions of this member.'
				),
	'email' => array (
				'key' => 'email',
				'meta_name' => '_mtemail',
				'label' => 'Email',
				'description' => 'Contact email for this member. Might be visible to public.'
				),
	'location' => array (
				'key' => 'location',
				'meta_name' => '_mtlocation',
				'label' => 'Location',
				'description' => 'Location/Origin/Adress of this member. Might be visible to public.'
				),
	'telephone' => array (
				'key' => 'telephone',
				'meta_name' => '_mttel',
				'label' => 'Telephone',
				'description' => 'Telephone contact. Might be visible to public.'
				),
	'user' => array (
				'key' => 'user',
				'meta_name' => '_mtuser',
				'label' => 'User/Author Profile',
				'description' => 'If this member is associated with a user account select it here. Might be used to fetch latest published posts in the single member page.'
				),
	'website' => array (
				'key' => 'website',
				'meta_name' => '_mtpersonal',
				'label' => 'Personal Website',
				'description' => 'URL to personal website. Might be visible to public.'
				),
	'name' => array (
				'key' => 'name',
				'meta_name' => 'title',
				'label' => 'Name/Title',
				'description' => 'Name of the entry.'
				),
	'photo' => array (
				'key' => 'photo',
				'meta_name' => 'featured_image',
				'label' => 'Photo/Image',
				'description' => 'Featured Image of the entry.'
				),
	'smallicons' => array (
				'key' => 'smallicons',
				'label' => 'Small Icons',
				'description' => 'Small CSS Icons.'
				),
	'socialicons' => array (
				'key' => 'socialicons',
				'label' => 'Social Icons',
				'description' => 'Social Icons.'
				),
	'filter' => array (
				'label' => 'Filter',
				'all-entries-label' => 'All',
	)

);




//array of styles for the images and text
//takes the corresponding shortcode code and the css class
//wrap styles for tables and grid should go here also

$myteam_wrapstyles = array(
	'normal-float' => 'mt-normal-float-wrap',
	'1-columns' => 'mt-responsive-wrap',
	'2-columns' => 'mt-responsive-wrap',
	'3-columns' => 'mt-responsive-wrap',
	'4-columns' => 'mt-responsive-wrap',
	'5-columns' => 'mt-responsive-wrap',
	'6-columns' => 'mt-responsive-wrap',
	'retro-box-theme' => 'mt-retro-style',
	'white-box-theme' => 'mt-white-box-style',
	'card-theme' => 'mt-theme-card-style',
	'odd-colored' => 'mt-table-odd-colored'
	);


$myteam_boxstyles = array(
	'img-left'=>'mt-float-left',
	'img-right'=>'mt-float-right',
	'normal-float' => 'mt-normal-float',
	'1-column' => 'mt-col_1',
	'2-columns' => 'mt-col_2',
	'3-columns' => 'mt-col_3',
	'4-columns' => 'mt-col_4',
	'5-columns' => 'mt-col_5',
	'6-columns' => 'mt-col_6'
	);
	
$myteam_innerboxstyles = array(
	'img-left'=>'mt-float-left',
	'img-right'=>'mt-float-right'
	);

$myteam_txtstyles = array(
	'text-left'=>'mt-align-left',
	'text-right'=>'mt-align-right',
	'text-center'=>'mt-align-center'
	);

$myteam_imgstyles = array(
		'img-rounded'=>'mt-rounded',
		'img-circle'=>'mt-circle',
		'img-square'=>'mt-square',
		'img-grayscale' =>'mt-grayscale',
		'img-grayscale-shadow' =>'mt-grayscale-shadow',
		'img-shadow' =>'mt-shadow',
		'img-left' =>'mt-img-left',
		'img-right' =>'mt-img-right',
		'img-white-border' => 'mt-white-border'
		);

$myteam_infostyles = array(
	'img-left'=>'mt-float-left',
	'img-right'=>'mt-float-right'
	);
	
$myteam_pagerstyles = array(
	'thumbs-left'=>'mt-pager-left',
	'thumbs-right'=>'mt-pager-right',
	'thumbs-below'=>'mt-pager-below'
	);

$myteam_pagerboxstyles = array(
	'thumbs-left'=>'mt-pager-box-right',
	'thumbs-right'=>'mt-pager-box-left',
	'thumbs-below'=>'mt-pager-box-above'
	);

$myteam_theme_names = array (
		'grid' => array(
			
			'default' => array (
								'key' => 'default',
								'name' => 'myteam-default-style',
								'link' => 'css/normal.css',
								'label' => 'Default'
								),
			'retro-box-theme' => array (
								'key' => 'retro-box-theme',
								'name' => 'myteam-retro-style',
								'link' => 'css/retro.css',
								'label' => 'Retro boxes'
								
								),
			'white-box-theme' => array (
								'key' => 'white-box-theme',
								'name' => 'myteam-white-box-style',
								'link' => 'css/white-box.css',
								'label' => 'White Box with Shadow'
								),
			'card-theme' => array (
								'key' => 'card-theme',
								'name' => 'myteam-card-theme-style',
								'link' => 'css/theme-card.css',
								'label' => 'Simple Card'
								)
			),
			
		'hover' => array(
		
		'default' => array (
							'key' => 'default',
							'name' => 'myteam-default-hover-style',
							'link' => 'css/normal-hover.css',
							'label' => 'Default'
							),
		'white-hover' => array (
							'key' => 'white-hover',
							'name' => 'myteam-white-hover-style',
							'link' => 'css/white-hover.css',
							'label' => 'White Hover'
							
							)
		),	
		'table' => array(
		
		'default' => array (
							'key' => 'default',
							'name' => 'myteam-default-table-style',
							'link' => 'css/table.css',
							'label' => 'Default'
							),
		'odd-colored' => array (
							'key' => 'odd-colored',
							'name' => 'myteam-odd-colored-table-style',
							'link' => 'css/table-odd-colored.css',
							'label' => 'Odd Rows Colored'
							
							)
		),	
		'pager' => array(
		
		'default' => array (
							'key' => 'default',
							'name' => 'myteam-default-pager-style',
							'link' => 'css/pager.css',
							'label' => 'Default'
							)
		)	

	);
?>