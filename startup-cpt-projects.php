<?php
/*
Plugin Name: StartUp Projects
Description: Le plugin pour activer le Custom Post Projects
Author: Yann Caplain
Version: 1.2.0
*/

//GitHub Plugin Updater
function startup_reloaded_projects_updater() {
	include_once 'lib/updater.php';
	//define( 'WP_GITHUB_FORCE_UPDATE', true );
	if ( is_admin() ) {
		$config = array(
			'slug' => plugin_basename( __FILE__ ),
			'proper_folder_name' => 'startup-cpt-projects',
			'api_url' => 'https://api.github.com/repos/yozzi/startup-cpt-projects',
			'raw_url' => 'https://raw.github.com/yozzi/startup-cpt-projects/master',
			'github_url' => 'https://github.com/yozzi/startup-cpt-projects',
			'zip_url' => 'https://github.com/yozzi/startup-cpt-projects/archive/master.zip',
			'sslverify' => true,
			'requires' => '3.0',
			'tested' => '3.3',
			'readme' => 'README.md',
			'access_token' => '',
		);
		new WP_GitHub_Updater( $config );
	}
}

add_action( 'init', 'startup_reloaded_projects_updater' );

//CPT
function startup_reloaded_projects() {
	$labels = array(
		'name'                => _x( 'Projects', 'Post Type General Name', 'text_domain' ),
		'singular_name'       => _x( 'Project', 'Post Type Singular Name', 'text_domain' ),
		'menu_name'           => __( 'Projects', 'text_domain' ),
		'name_admin_bar'      => __( 'Projects', 'text_domain' ),
		'parent_item_colon'   => __( 'Parent Item:', 'text_domain' ),
		'all_items'           => __( 'All Items', 'text_domain' ),
		'add_new_item'        => __( 'Add New Item', 'text_domain' ),
		'add_new'             => __( 'Add New', 'text_domain' ),
		'new_item'            => __( 'New Item', 'text_domain' ),
		'edit_item'           => __( 'Edit Item', 'text_domain' ),
		'update_item'         => __( 'Update Item', 'text_domain' ),
		'view_item'           => __( 'View Item', 'text_domain' ),
		'search_items'        => __( 'Search Item', 'text_domain' ),
		'not_found'           => __( 'Not found', 'text_domain' ),
		'not_found_in_trash'  => __( 'Not found in Trash', 'text_domain' )
	);
	$args = array(
		'label'               => __( 'projects', 'text_domain' ),
		'description'         => __( 'Post Type Description', 'text_domain' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'revisions', ),
		//'taxonomies'          => array( 'project_types' ),
		'hierarchical'        => true,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_position'       => 5,
		'menu_icon'           => 'dashicons-building',
		'show_in_admin_bar'   => false,
		'show_in_nav_menus'   => true,
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
        'capability_type'     => array('project','projects'),
        'map_meta_cap'        => true
	);
	register_post_type( 'projects', $args );

}

add_action( 'init', 'startup_reloaded_projects', 0 );

// Capabilities
function startup_reloaded_projects_caps() {
	$role_admin = get_role( 'administrator' );
	$role_admin->add_cap( 'edit_project' );
	$role_admin->add_cap( 'read_project' );
	$role_admin->add_cap( 'delete_project' );
	$role_admin->add_cap( 'edit_others_projects' );
	$role_admin->add_cap( 'publish_projects' );
	$role_admin->add_cap( 'edit_projects' );
	$role_admin->add_cap( 'read_private_projects' );
	$role_admin->add_cap( 'delete_projects' );
	$role_admin->add_cap( 'delete_private_projects' );
	$role_admin->add_cap( 'delete_published_projects' );
	$role_admin->add_cap( 'delete_others_projects' );
	$role_admin->add_cap( 'edit_private_projects' );
	$role_admin->add_cap( 'edit_published_projects' );
}

register_activation_hook( __FILE__, 'startup_reloaded_projects_caps' );

// Project types taxonomy
function startup_reloaded_project_types() {
	$labels = array(
		'name'                       => _x( 'Project Types', 'Taxonomy General Name', 'text_domain' ),
		'singular_name'              => _x( 'Project Type', 'Taxonomy Singular Name', 'text_domain' ),
		'menu_name'                  => __( 'Project Types', 'text_domain' ),
		'all_items'                  => __( 'All Items', 'text_domain' ),
		'parent_item'                => __( 'Parent Item', 'text_domain' ),
		'parent_item_colon'          => __( 'Parent Item:', 'text_domain' ),
		'new_item_name'              => __( 'New Item Name', 'text_domain' ),
		'add_new_item'               => __( 'Add New Item', 'text_domain' ),
		'edit_item'                  => __( 'Edit Item', 'text_domain' ),
		'update_item'                => __( 'Update Item', 'text_domain' ),
		'view_item'                  => __( 'View Item', 'text_domain' ),
		'separate_items_with_commas' => __( 'Separate items with commas', 'text_domain' ),
		'add_or_remove_items'        => __( 'Add or remove items', 'text_domain' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'text_domain' ),
		'popular_items'              => __( 'Popular Items', 'text_domain' ),
		'search_items'               => __( 'Search Items', 'text_domain' ),
		'not_found'                  => __( 'Not Found', 'text_domain' )
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => false,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => false
	);
	register_taxonomy( 'project-type', array( 'projects' ), $args );

}

add_action( 'init', 'startup_reloaded_project_types', 0 );

// Retirer la boite de la taxonomie sur le coté
function startup_reloaded_project_types_metabox_remove() {
	remove_meta_box( 'tagsdiv-project-type', 'projects', 'side' );
    // tagsdiv-project_types pour les taxonomies type tags
    // custom_taxonomy_slugdiv pour les taxonomies type categories
}

add_action( 'admin_menu' , 'startup_reloaded_project_types_metabox_remove' );

// Metaboxes
function startup_reloaded_projects_meta() {
	// Start with an underscore to hide fields from custom fields list
	$prefix = '_startup_reloaded_projects_';

	$cmb_box = new_cmb2_box( array(
		'id'            => $prefix . 'metabox',
		'title'         => __( 'Project details', 'cmb2' ),
		'object_types'  => array( 'projects' )
	) );
    
    $cmb_box->add_field( array(
		'name' => __( 'Main picture', 'cmb2' ),
		'desc' => __( 'Main image of the project, may be different from the thumbnail. i.e. 3D model', 'cmb2' ),
		'id'   => $prefix . 'main_pic',
		'type' => 'file',
        // Optionally hide the text input for the url:
        'options' => array(
            'url' => false,
        ),
	) );
    
    $cmb_box->add_field( array(
		'name' => __( 'Thumbnail', 'cmb2' ),
		'desc' => __( 'The project picture on your website listings, if different from Main picture.', 'cmb2' ),
		'id'   => $prefix . 'thumbnail',
		'type' => 'file',
        // Optionally hide the text input for the url:
        'options' => array(
            'url' => false,
        ),
	) );

	$cmb_box->add_field( array(
		'name'       => __( 'Short description', 'cmb2' ),
		'desc'       => __( 'i.e. "New business building in Montreal"', 'cmb2' ),
		'id'         => $prefix . 'short',
		'type'       => 'text'
	) );
    
    $cmb_box->add_field( array(
		'name'     => __( 'Type', 'cmb2' ),
		'desc'     => __( 'Select the type(s) of the project', 'cmb2' ),
		'id'       => $prefix . 'type',
		'type'     => 'taxonomy_multicheck',
		'taxonomy' => 'project-type', // Taxonomy Slug
		'inline'  => true // Toggles display to inline
	) );
    
    $cmb_box->add_field( array(
		'name'             => __( 'Status', 'cmb2' ),
		'desc'             => __( 'The project\'s current status', 'cmb2' ),
		'id'               => $prefix . 'status',
		'type'             => 'select',
		'show_option_none' => true,
		'options'          => array(
			'Prévente' => __( 'Prévente', 'cmb2' ),
			'Vendu'   => __( 'Vendu', 'cmb2' ),
			'Prix révisé'     => __( 'Prix révisé', 'cmb2' ),
            'Une unité disponible'   => __( 'Une unité disponible', 'cmb2' ),
			'Deux unités disponibles'     => __( 'Deux unités disponibles', 'cmb2' ),
		),
	) );
    
    $cmb_box->add_field( array(
		'name' => __( 'Description', 'cmb2' ),
		'desc' => __( 'Full, main description', 'cmb2' ),
		'id'   => $prefix . 'description',
		'type' => 'textarea'
	) );
    
    $cmb_box->add_field( array(
		'name'       => __( 'Specifications', 'cmb2' ),
		'id'         => $prefix . 'specifications',
		'type'       => 'text',
		'repeatable'      => true
	) );
    
    $cmb_box->add_field( array(
		'name'       => __( 'Available options', 'cmb2' ),
		'id'         => $prefix . 'options',
		'type'       => 'text',
		'repeatable'      => true
	) );
    
    $cmb_box->add_field( array(
        'name' => 'Location',
        'desc' => 'Drag the marker to set the exact location',
        'id' => $prefix . 'gmap',
        'type' => 'pw_map',
        // 'split_values' => true, // Save latitude and longitude as two separate fields
    ) );
    
    $cmb_box->add_field( array(
        'name'        => 'Zoom',
        'desc'        => 'Zoom value of your Google Map.',
        'id'          => $prefix . 'zoom',
        'type'        => 'own_slider',
        'min'         => '0',
        'max'         => '21',
        'default'     => '12', // start value
        'value_label' => ''
    ) );
    
    $cmb_box->add_field( array(
		'name'       => __( 'Warranty', 'cmb2' ),
		'desc'       => __( 'i.e. "5 years by Qualité Habitation"', 'cmb2' ),
		'id'         => $prefix . 'warranty',
		'type'       => 'text'
	) );

	$cmb_box->add_field( array(
		'name' => __( 'Price', 'cmb2' ),
		'desc' => __( 'The project price in Canadian Dollar', 'cmb2' ),
		'id'   => $prefix . 'price',
		'type' => 'text_money'
	) );

    $cmb_box->add_field( array(
		'name' => __( 'Implantation', 'cmb2' ),
		'desc' => __( 'Image file of the certificate of implantation', 'cmb2' ),
		'id'   => $prefix . 'implantation',
		'type' => 'file',
        // Optionally hide the text input for the url:
        'options' => array(
            'url' => false,
        ),
	) );

	$cmb_box->add_field( array(
		'name'         => __( 'Plans', 'cmb2' ),
		'desc'         => __( 'Upload or add multiple images for project plans.', 'cmb2' ),
		'id'           => $prefix . 'plans',
		'type'         => 'file_list',
		'preview_size' => array( 100, 100 ) // Default: array( 50, 50 )
	) );
    
    $cmb_box->add_field( array(
		'name'         => __( 'Gallery', 'cmb2' ),
		'desc'         => __( 'Upload or add multiple images for project photo gallery.', 'cmb2' ),
		'id'           => $prefix . 'gallery',
		'type'         => 'file_list',
		'preview_size' => array( 100, 100 ) // Default: array( 50, 50 )
	) );
    
    $cmb_box->add_field( array(
		'name' => __( 'External url', 'cmb2' ),
		'desc' => __( 'Link to te project on an extrenal website (i.e. real estate agency)', 'cmb2' ),
		'id'   => $prefix . 'url',
		'type' => 'text_url'
	) );
}

add_action( 'cmb2_init', 'startup_reloaded_projects_meta' );
?>