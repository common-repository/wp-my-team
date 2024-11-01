<?php
/*
	Author: Selvabalajee
	Version: 1.1
	Author URI:http://www.tbsin.com/
	Packages : sorting team member 
*/

class myteam_sorting {

	public function __construct() {
		add_action( 'load-edit.php', array( $this, 'load_edit_screen' ) );
		add_action( 'wp_ajax_simple_page_sorting', array( $this, 'ajax_simple_page_sorting' ) );
	}

	public function load_edit_screen() {
		$screen = get_current_screen();
		$post_type = $screen->post_type;
		
		if($post_type == 'myteam') {	
			add_filter( 'views_' . $screen->id, array( $this, 'sort_by_order_link' )  );		
			add_action( 'wp', array( $this, 'wp' ) );
			}
		else {
			return;
		}
	}

	public function wp() {
		if ( 0 === strpos( get_query_var('orderby'), 'menu_order' ) && isset($_GET['dd']) &&($_GET['dd']=='true' ) ) {	
			$script_name =  'js/order/simple-page-sorting.js';
			wp_enqueue_script( 'simple-page-sorting', plugins_url( $script_name, __FILE__ ), array('jquery-ui-sortable'), '2.1', true );
		}
			wp_enqueue_style( 'simple-page-sorting', plugins_url( 'js/order/simple-page-sorting.css', __FILE__ ) );		
			wp_deregister_style( 'myteam-smallicons' );
			wp_register_style( 'myteam-smallicons', plugins_url( '/css/font-awesome/css/font-awesome.min.css', __FILE__ ),array(),false,false);
			wp_enqueue_style( 'myteam-smallicons' );	
	}

	
	public function ajax_simple_page_sorting() {
		if ( empty( $_POST['id'] ) || ( !isset( $_POST['previd'] ) && !isset( $_POST['nextid'] ) ) )
			die(-1);

		if ( ! $post = get_post( $_POST['id'] ) )
			die(-1);

		if ( ! $this->check_edit_others_caps( $post->post_type ) )
			die(-1);

		if ( !defined( 'WP_DEBUG' ) || !WP_DEBUG )
			error_reporting( 0 );

		$previd = empty( $_POST['previd'] ) ? false : (int) $_POST['previd'];
		$nextid = empty( $_POST['nextid'] ) ? false : (int) $_POST['nextid'];
		$start = empty( $_POST['start'] ) ? 1 : (int) $_POST['start'];
		$excluded = empty( $_POST['excluded'] ) ? array( $post->ID ) : array_filter( (array) $_POST['excluded'], 'intval' );

		$new_pos = array(); // store new positions for ajax
		$return_data = new stdClass;

		do_action( 'simple_page_sorting_pre_order_posts', $post, $start );

		$parent_id = $post->post_parent;
		$next_post_parent = $nextid ? wp_get_post_parent_id( $nextid ) : false;
		
		if ( $next_post_parent !== $parent_id )
			$nextid = false;

		$max_sortable_posts = (int) apply_filters( 'simple_page_sorting_limit', 50 );	// should reliably be able to do about 50 at a time
		if ( $max_sortable_posts < 5 )	
			$max_sortable_posts = 50;

		$post_stati = get_post_stati(array(
			'show_in_admin_all_list' => true,
		));

		$individuals = new WP_Query(array(
			'depth'						=> 1,
			'posts_per_page'			=> $max_sortable_posts,
			'post_type' 				=> $post->post_type,
			'post_status' 				=> $post_stati,
			'post_parent' 				=> $parent_id,
			'orderby' 					=> 'menu_order title',
			'order' 					=> 'ASC',
			'post__not_in'				=> $excluded,
			'update_post_term_cache'	=> false,
			'update_post_meta_cache'	=> false,
			'suppress_filters' 			=> true,
			'ignore_sticky_posts'		=> true,
		)); 

		remove_action( 'pre_post_update', 'wp_save_post_revision' );

		foreach( $individuals->posts as $individual ) :

			if ( $individual->ID === $post->ID )
				continue;

			if ( $nextid === $individual->ID ) {
				wp_update_post(array(
					'ID'			=> $post->ID,
					'menu_order'	=> $start,
					'post_parent'	=> $parent_id,
				));
				$ancestors = get_post_ancestors( $post->ID );
				$new_pos[$post->ID] = array(
					'menu_order'	=> $start,
					'post_parent'	=> $parent_id,
					'depth'			=> count( $ancestors ),
				);
				$start++;
			}

			if ( isset( $new_pos[$post->ID] ) && $individual->menu_order >= $start ) {
				$return_data->next = false;
				break;
			}

			if ( $individual->menu_order != $start ) {
				wp_update_post(array(
					'ID' 			=> $individual->ID,
					'menu_order'	=> $start,
				));
			}
			$new_pos[$individual->ID] = $start;
			$start++;

			if ( !$nextid && $previd == $individual->ID ) {
				wp_update_post(array(
					'ID' 			=> $post->ID,
					'menu_order' 	=> $start,
					'post_parent' 	=> $parent_id
				));
				$ancestors = get_post_ancestors( $post->ID );
				$new_pos[$post->ID] = array(
					'menu_order'	=> $start,
					'post_parent' 	=> $parent_id,
					'depth' 		=> count($ancestors) );
				$start++;
			}

		endforeach;

		if ( !isset( $return_data->next ) && $individuals->max_num_pages > 1 ) {
			$return_data->next = array(
				'id' 		=> $post->ID,
				'previd' 	=> $previd,
				'nextid' 	=> $nextid,
				'start'		=> $start,
				'excluded'	=> array_merge( array_keys( $new_pos ), $excluded ),
			);
		} else {
			$return_data->next = false;
		}

		do_action( 'simple_page_sorting_ordered_posts', $post, $new_pos );
		if ( ! $return_data->next ) {
			$children = get_posts(array(
				'numberposts'				=> 1,
				'post_type' 				=> $post->post_type,
				'post_status' 				=> $post_stati,
				'post_parent' 				=> $post->ID,
				'fields'					=> 'ids',
				'update_post_term_cache'	=> false,
				'update_post_meta_cache'	=> false,
			));

			if ( ! empty( $children ) )
				die( 'children' );
		}

		$return_data->new_pos = $new_pos;

		die( json_encode( $return_data ) );
	}

	public function sort_by_order_link( $views ) {
		$class = ( get_query_var('orderby') == 'menu_order title' && isset($_GET['dd']) && $_GET['dd'] == 'true'  ) ? 'myteam-active-drag' : 'myteam-inactive-drag';
		$query_string = remove_query_arg(array( 'orderby', 'order' ));
		$query_string = add_query_arg( 'orderby', urlencode('menu_order title'), $query_string );
		$query_string = add_query_arg( 'dd', 'true', $query_string );
		$query_string_inactive = remove_query_arg('dd');
		
		if ( 0 === strpos( get_query_var('orderby'), 'menu_order' ) && isset($_GET['dd']) && ($_GET['dd']=='true' ) ) {	
			$views['byorder'] = '<span class="' . $class . '">Drag and Drop Sorting Activated</span> <i class="icon-move"></i>  <i style="color:#888;">Sorting order will be saved automatically.</i> <a href ="'. $query_string_inactive .'" class="myteam-inactive-drag"> De-activate Drag&Drop </a> ';
		}
		else {
			$views['byorder'] = '<a href="'. $query_string . '" class="' . $class . '">Activate Drag & Drop Sorting</a> <i class="icon-move"></i> ';
		}
		
		return $views;
	}

	public function check_edit_others_caps( $post_type ) {
		$post_type_object = get_post_type_object( $post_type );
		$edit_others_cap = empty( $post_type_object ) ? 'edit_others_' . $post_type . 's' : $post_type_object->cap->edit_others_posts;
		return apply_filters( 'simple_page_sorting_edit_rights', current_user_can( $edit_others_cap ), $post_type );
	}
}
$myteam_sorting = new myteam_sorting;
?>