<?php

/**
 * Add Announcement custom post type and taxonomy
 */

add_action( 'init', 'create_mytti_announcement_post_type' );

function create_mytti_announcement_post_type() {
	register_post_type( 'mytti_announcement',
		array(
			'labels' => array(
				'name' => __( 'Announcements' ),
				'singular_name' => __( 'Announcement' ),
				'add_new_item' => __( 'Add New Announcement' ),
			),
			'menu_icon' => MyTTI_Announcements\PLUGIN_URL . '../img/announcement.png',
			'menu_position' => 50,
			'public' => true,
			'has_archive' => true,
			'rewrite' => array(
				'slug' => 'announcement'
			),
			'supports' => array(
				'title',
				'editor',
				'excerpt',
				'revisions',
				'author',
			),
			'taxonomies' => array( 'mytti_announcement_categories' ),
			'capability_type' => 'mytti_announcement',
			'capabilities' => array(
				'publish_posts' => 'publish_mytti_announcements',
				'edit_posts' => 'edit_mytti_announcements',
				'edit_others_posts' => 'edit_others_mytti_announcements',
				'delete_posts' => 'delete_mytti_announcements',
				'delete_others_posts' => 'delete_others_mytti_announcements',
				'read_private_posts' => 'read_private_mytti_announcements',
			),
		)
	);
}

function register_mytti_announcement_categories() {
	register_taxonomy(
		'mytti_announcement_categories',
		'mytti_announcement',
		array(
			'hierarchical' => true,
			'label' => 'Categories',
			'query' => true,
			'rewrite' => true
		)
	);
}

add_action( 'init', 'register_mytti_announcement_categories' );

add_filter( 'map_meta_cap', 'mytti_announcement_map_meta_cap', 10, 4);

function mytti_announcement_map_meta_cap( $caps, $cap, $user_id, $args ) {
	if( 'edit_mytti_announcement' == $cap || 'delete_mytti_announcement' == $cap || 'read_mytti_announcement' == $cap ) {
		$post = get_post( $args[0] );
		$post_type = get_post_type_object( $post->post_type );
		$caps = array();
		
		switch( $cap ) {
			case 'edit_mytti_announcement':
				$caps[] = ( $user_id == $post->post_author ) ? $post_type->cap->edit_posts : $post_type->cap->edit_others_posts;
				break;
			case 'delete_mytti_announcement':
				$caps[] = ( $user_id == $post->post_author ) ? $post_type->cap->delete_posts : $post_type->cap->delete_others_posts;
				break;
			case 'read_mytti_announcement':
				$caps[] = ( 'private' != $post->post_status || $user_id == $post->post_author ) ? $caps[] = 'read' : $post_type->cap->read_private_posts;
				break;
		}
	}
	
	return $caps;
}

add_action( 'admin_init', 'mytti_announcement_role_set' );

function mytti_announcement_role_set() {
	global $wp_roles;

	add_role( 'mytti_announcement_manager', 'Announcements Manager', array(
		'edit_mytti_announcements' => TRUE,
		'edit_others_mytti_announcements' => TRUE,
		'delete_mytti_announcements' => TRUE,
		'delete_others_mytti_announcements' => TRUE,
		'read_privte_mytti_announcements' => TRUE,
		'read' => TRUE,
		'level_0' => TRUE
		)
	);

	$role = get_role( 'administrator' );
	$role->add_cap( 'publish_mytti_announcements' );
	$role->add_cap( 'edit_mytti_announcements' );
	$role->add_cap( 'edit_others_mytti_announcements' );
	$role->add_cap( 'delete_mytti_announcements' );
	$role->add_cap( 'delete_others_mytti_announcements' );
	$role->add_cap( 'read_private_mytti_announcements' );

	$role = get_role( 'editor' );
	$role->add_cap( 'publish_mytti_announcements' );
	$role->add_cap( 'edit_mytti_announcements' );
	$role->add_cap( 'edit_others_mytti_announcements' );
	$role->add_cap( 'delete_mytti_announcements' );
	$role->add_cap( 'delete_others_mytti_announcements' );
	$role->add_cap( 'read_private_mytti_announcements' );

	$role = get_role( 'author' );
	$role->add_cap( 'publish_mytti_announcements' );
	$role->add_cap( 'edit_mytti_announcements' );
	$role->add_cap( 'delete_mytti_announcements' );
	$role->add_cap( 'read_mytti_announcements' );

	$role = get_role( 'contributor' );
	$role->add_cap( 'edit_mytti_announcements' );
	$role->add_cap( 'delete_mytti_announcements' );
	$role->add_cap( 'read_mytti_announcements' );

	$role = get_role( 'subscriber' );
	$role->add_cap( 'edit_mytti_announcements' );
	$role->add_cap( 'delete_mytti_announcements' );
	$role->add_cap( 'read_mytti_announcements' );
}

add_action( 'add_meta_boxes', 'create_mytti_announcement_custom_box' );
add_action( 'save_post', 'mytti_announcement_save_postdata' );

function create_mytti_announcement_custom_box() {
	add_meta_box( 	'mytti_announcement_options',
					__( 'Announcement Options' ),
					'mytti_announcement_custom_box',
					'mytti_announcement',
					'side',
					'core'
	);
}

function mytti_announcement_custom_box( $post ) {
	$startdate = get_post_meta( $post->ID, 'mytti_announcement_start_date', TRUE );
	$enddate = get_post_meta( $post->ID, 'mytti_announcement_end_date', TRUE );
?>
	<table>
		<tr>
			<th><label for="mytti_announcement_start_date"><?php _e( 'Start Date' ); ?></label></th>
			<td><input type="text" name="mytti_announcement_start_date" value="<?php echo $startdate ? date( 'm/d/Y', $startdate ) : NULL; ?>" /></td>
		</tr>

		<tr>
			<th><label for="mytti_announcement_end_date"><?php _e( 'End Date' ); ?></label></th>
			<td><input type="text" name="mytti_announcement_end_date" value="<?php echo $enddate ? date( 'm/d/Y', $enddate ) : NULL; ?>" /></td>
		</tr>
	</table>

	<script>
		jQuery(function($) {
			$( "input[name='mytti_announcement_start_date']" ).datepicker({
				minDate: 0
			});
			$( "input[name='mytti_announcement_end_date']" ).datepicker({
				minDate: 0
			});
		});
	</script>

<?php
}

function mytti_announcement_save_postdata( $post_id ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
		return;
	
	if ( isset( $_POST['post_type'] ) && 'mytti_announcement' == $_POST['post_type'] ) {
		if ( !current_user_can( 'edit_mytti_announcement', $post_id ) )
			return;
	}
	
	if ( isset( $_POST['mytti_announcement_start_date'] ) ) {
		$start_date = strtotime( $_POST['mytti_announcement_start_date'] . ' 00:00:00' );
		update_post_meta( $post_id, 'mytti_announcement_start_date', $start_date );
	}
	
	if ( isset( $_POST['mytti_announcement_end_date'] ) ) {
		$end_date = strtotime( $_POST['mytti_announcement_end_date'] . ' 23:59:59' );
		update_post_meta( $post_id, 'mytti_announcement_end_date', $end_date );
	}
}