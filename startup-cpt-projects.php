<?php
/*
Plugin Name: StartUp CPT Projects
Description: Le plugin pour activer le Custom Post Projects
Author: Yann Caplain
Version: 1.2.0
Text Domain: startup-cpt-projects
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
		'name'                => _x( 'Projects', 'Post Type General Name', 'startup-cpt-projects' ),
		'singular_name'       => _x( 'Project', 'Post Type Singular Name', 'startup-cpt-projects' ),
		'menu_name'           => __( 'Projects (b)', 'startup-cpt-projects' ),
		'name_admin_bar'      => __( 'Projects', 'startup-cpt-projects' ),
		'parent_item_colon'   => __( 'Parent Item:', 'startup-cpt-projects' ),
		'all_items'           => __( 'All Items', 'startup-cpt-projects' ),
		'add_new_item'        => __( 'Add New Item', 'startup-cpt-projects' ),
		'add_new'             => __( 'Add New', 'startup-cpt-projects' ),
		'new_item'            => __( 'New Item', 'startup-cpt-projects' ),
		'edit_item'           => __( 'Edit Item', 'startup-cpt-projects' ),
		'update_item'         => __( 'Update Item', 'startup-cpt-projects' ),
		'view_item'           => __( 'View Item', 'startup-cpt-projects' ),
		'search_items'        => __( 'Search Item', 'startup-cpt-projects' ),
		'not_found'           => __( 'Not found', 'startup-cpt-projects' ),
		'not_found_in_trash'  => __( 'Not found in Trash', 'startup-cpt-projects' )
	);
	$args = array(
		'label'               => __( 'projects', 'startup-cpt-projects' ),
		'description'         => __( 'Post Type Description', 'startup-cpt-projects' ),
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

//Flusher les permalink à l'activation du plugin pour qu'ils fonctionnent sans mise à jour manuelle
function startup_reloaded_projects_rewrite_flush() {
    startup_reloaded_projects();
    flush_rewrite_rules();
}

register_activation_hook( __FILE__, 'startup_reloaded_projects_rewrite_flush' );

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
		'name'                       => _x( 'Project Types', 'Taxonomy General Name', 'startup-cpt-projects' ),
		'singular_name'              => _x( 'Project Type', 'Taxonomy Singular Name', 'startup-cpt-projects' ),
		'menu_name'                  => __( 'Project Types', 'startup-cpt-projects' ),
		'all_items'                  => __( 'All Items', 'startup-cpt-projects' ),
		'parent_item'                => __( 'Parent Item', 'startup-cpt-projects' ),
		'parent_item_colon'          => __( 'Parent Item:', 'startup-cpt-projects' ),
		'new_item_name'              => __( 'New Item Name', 'startup-cpt-projects' ),
		'add_new_item'               => __( 'Add New Item', 'startup-cpt-projects' ),
		'edit_item'                  => __( 'Edit Item', 'startup-cpt-projects' ),
		'update_item'                => __( 'Update Item', 'startup-cpt-projects' ),
		'view_item'                  => __( 'View Item', 'startup-cpt-projects' ),
		'separate_items_with_commas' => __( 'Separate items with commas', 'startup-cpt-projects' ),
		'add_or_remove_items'        => __( 'Add or remove items', 'startup-cpt-projects' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'startup-cpt-projects' ),
		'popular_items'              => __( 'Popular Items', 'startup-cpt-projects' ),
		'search_items'               => __( 'Search Items', 'startup-cpt-projects' ),
		'not_found'                  => __( 'Not Found', 'startup-cpt-projects' )
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
		'title'         => __( 'Project details', 'startup-cpt-projects' ),
		'object_types'  => array( 'projects' )
	) );
    
    $cmb_box->add_field( array(
		'name' => __( 'Main picture', 'startup-cpt-projects' ),
		'desc' => __( 'Main image of the project, may be different from the thumbnail. i.e. 3D model', 'startup-cpt-projects' ),
		'id'   => $prefix . 'main_pic',
		'type' => 'file',
        // Optionally hide the text input for the url:
        'options' => array(
            'url' => false,
        ),
	) );
    
    $cmb_box->add_field( array(
		'name' => __( 'Thumbnail', 'startup-cpt-projects' ),
		'desc' => __( 'The project picture on your website listings, if different from Main picture.', 'startup-cpt-projects' ),
		'id'   => $prefix . 'thumbnail',
		'type' => 'file',
        // Optionally hide the text input for the url:
        'options' => array(
            'url' => false,
        ),
	) );

	$cmb_box->add_field( array(
		'name'       => __( 'Short description', 'startup-cpt-projects' ),
		'desc'       => __( 'i.e. "New business building in Montreal"', 'startup-cpt-projects' ),
		'id'         => $prefix . 'short',
		'type'       => 'text'
	) );
    
    $cmb_box->add_field( array(
		'name'     => __( 'Type', 'startup-cpt-projects' ),
		'desc'     => __( 'Select the type(s) of the project', 'startup-cpt-projects' ),
		'id'       => $prefix . 'type',
		'type'     => 'taxonomy_multicheck',
		'taxonomy' => 'project-type', // Taxonomy Slug
		'inline'  => true // Toggles display to inline
	) );
    
    $cmb_box->add_field( array(
		'name'             => __( 'Status', 'startup-cpt-projects' ),
		'desc'             => __( 'The project\'s current status', 'startup-cpt-projects' ),
		'id'               => $prefix . 'status',
		'type'             => 'select',
		'show_option_none' => true,
		'options'          => array(
			'Prévente' => __( 'Prévente', 'startup-cpt-projects' ),
			'Vendu'   => __( 'Vendu', 'startup-cpt-projects' ),
			'Prix révisé'     => __( 'Prix révisé', 'startup-cpt-projects' ),
            'Une unité disponible'   => __( 'Une unité disponible', 'startup-cpt-projects' ),
			'Deux unités disponibles'     => __( 'Deux unités disponibles', 'startup-cpt-projects' ),
		),
	) );
    
    $cmb_box->add_field( array(
		'name' => __( 'Description', 'startup-cpt-projects' ),
		'desc' => __( 'Full, main description', 'startup-cpt-projects' ),
		'id'   => $prefix . 'description',
		'type' => 'textarea'
	) );
    
    $cmb_box->add_field( array(
		'name'       => __( 'Specifications', 'startup-cpt-projects' ),
		'id'         => $prefix . 'specifications',
		'type'       => 'text',
		'repeatable'      => true
	) );
    
    $cmb_box->add_field( array(
		'name'       => __( 'Available options', 'startup-cpt-projects' ),
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
		'name'       => __( 'Warranty', 'startup-cpt-projects' ),
		'desc'       => __( 'i.e. "5 years by Qualité Habitation"', 'startup-cpt-projects' ),
		'id'         => $prefix . 'warranty',
		'type'       => 'text'
	) );

	$cmb_box->add_field( array(
		'name' => __( 'Price', 'startup-cpt-projects' ),
		'desc' => __( 'The project price in Canadian Dollar', 'startup-cpt-projects' ),
		'id'   => $prefix . 'price',
		'type' => 'text_money'
	) );

    $cmb_box->add_field( array(
		'name' => __( 'Implantation', 'startup-cpt-projects' ),
		'desc' => __( 'Image file of the certificate of implantation', 'startup-cpt-projects' ),
		'id'   => $prefix . 'implantation',
		'type' => 'file',
        // Optionally hide the text input for the url:
        'options' => array(
            'url' => false,
        ),
	) );

	$cmb_box->add_field( array(
		'name'         => __( 'Plans', 'startup-cpt-projects' ),
		'desc'         => __( 'Upload or add multiple images for project plans.', 'startup-cpt-projects' ),
		'id'           => $prefix . 'plans',
		'type'         => 'file_list',
		'preview_size' => array( 100, 100 ) // Default: array( 50, 50 )
	) );
    
    $cmb_box->add_field( array(
		'name'         => __( 'Gallery', 'startup-cpt-projects' ),
		'desc'         => __( 'Upload or add multiple images for project photo gallery.', 'startup-cpt-projects' ),
		'id'           => $prefix . 'gallery',
		'type'         => 'file_list',
		'preview_size' => array( 100, 100 ) // Default: array( 50, 50 )
	) );
    
    $cmb_box->add_field( array(
		'name' => __( 'External url', 'startup-cpt-projects' ),
		'desc' => __( 'Link to te project on an extrenal website (i.e. real estate agency)', 'startup-cpt-projects' ),
		'id'   => $prefix . 'url',
		'type' => 'text_url'
	) );
}

add_action( 'cmb2_admin_init', 'startup_reloaded_projects_meta' );

// Shortcode
function startup_reloaded_projects_shortcode( $atts ) {

	// Attributes
    $atts = shortcode_atts(array(
            'bg' => ''
        ), $atts);
    
	// Code
        ob_start();
        require get_template_directory() . '/template-parts/content-projects.php';
        return ob_get_clean();    
}
add_shortcode( 'projects', 'startup_reloaded_projects_shortcode' );
?>