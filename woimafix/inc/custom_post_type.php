<?php

// Register Team Post Type
function create_provider_post_type() {
    $labels = array(
      'name' => __( 'Providers' ),
      'singular_name' => __( 'Provider' ),
      'add_new' => __( 'New Provider' ),
      'add_new_item' => __( 'Add New Provider' ),
      'edit_item' => __( 'Edit Provider' ),
      'new_item' => __( 'New Provider' ),
      'view_item' => __( 'View Provider' ),
      'search_items' => __( 'Search Provider' ),
      'not_found' =>  __( 'No Provider Found' ),
      'not_found_in_trash' => __( 'No provider found in Trash' ),
      );
    $args = array(
      'labels' => $labels,
      'has_archive' => true,
      'public' => true,
      'hierarchical' => false,
      'menu_position' => 5,
      'menu_icon' => 'dashicons-cart',
      'show_ui'             => true,
      'show_in_menu'        => true,
      'show_in_nav_menus'   => true,
      'show_in_admin_bar'   => true,
      'menu_icon'           => 'dashicons-groups',
      'can_export'          => true,
      'exclude_from_search' => false,
      'publicly_queryable'  => true,
      'capability_type'     => 'page',
      'supports' => array(
        'title',
        'excerpt',
        'thumbnail'
        ),
      );
    register_post_type( 'provider', $args );
  }
  add_action( 'init', 'create_provider_post_type' );

  function location_register_taxonomy() {
    register_taxonomy( 'locations', 'provider',
      array(
        'labels' => array(
          'name'              => 'Locations',
          'singular_name'     => 'Location',
          'search_items'      => 'Search Location',
          'all_items'         => 'All Locations',
          'edit_item'         => 'Edit Location',
          'update_item'       => 'Update location',
          'add_new_item'      => 'Add New Location',
          'new_item_name'     => 'New Location Name',
          'menu_name'         => 'Locations',
          ),
        'hierarchical' => true,
        'sort' => true,
        'args' => array( 'orderby' => 'term_order' ),
        'show_admin_column' => true
        )
      );
  }
  add_action( 'init', 'location_register_taxonomy' );

  function provider_register_taxonomy() {
    register_taxonomy( 'provider_category', 'provider',
      array(
        'labels' => array(
          'name'              => 'Categories',
          'singular_name'     => 'Categorу',
          'search_items'      => 'Search Categorу',
          'all_items'         => 'All Categories',
          'edit_item'         => 'Edit Category',
          'update_item'       => 'Update Category',
          'add_new_item'      => 'Add New Category',
          'new_item_name'     => 'New Category Name',
          'menu_name'         => 'Categories',
          ),
        'hierarchical' => true,
        'sort' => true,
        'args' => array( 'orderby' => 'term_order' ),
        'show_admin_column' => true
        )
      );
  }
  add_action( 'init', 'provider_register_taxonomy' );

// Register services Post Type
function create_services_post_type() {
  $labels = array(
    'name' => __( 'Services' ),
    'singular_name' => __( 'Service' ),
    'add_new' => __( 'New Service' ),
    'add_new_item' => __( 'Add New Service' ),
    'edit_item' => __( 'Edit Service' ),
    'new_item' => __( 'New Item' ),
    'view_item' => __( 'View Item' ),
    'search_items' => __( 'Search services' ),
    'not_found' =>  __( 'No services found' ),
    'not_found_in_trash' => __( 'No services found in Trash' ),
    );
  $args = array(
    'labels' => $labels,
    'has_archive' => true,
    'public' => true,
    'hierarchical' => false,
    'menu_position' => 4,
    'show_ui'             => true,
    'show_in_menu'        => true,
    'show_in_nav_menus'   => true,
    'show_in_admin_bar'   => true,
    'menu_icon'           => 'dashicons-store',
    'can_export'          => true,
    'exclude_from_search' => false,
    'publicly_queryable'  => true,
    'capability_type'     => 'page',
    'supports' => array(
      'title',
      'excerpt',
      'custom-fields',
      'thumbnail',
      'author'
      ),
    );
  register_post_type( 'services', $args );
}
add_action( 'init', 'create_services_post_type' );

function services_register_taxonomy() {
  register_taxonomy( 'services_cat', 'services',
    array(
      'labels' => array(
        'name'              => 'Categories',
        'singular_name'     => 'Category',
        'search_items'      => 'Search Category',
        'all_items'         => 'All Category',
        'edit_item'         => 'Edit Category',
        'update_item'       => 'Update Category',
        'add_new_item'      => 'Add New Category',
        'new_item_name'     => 'New Category Name',
        'menu_name'         => 'Categories',
        ),
      'hierarchical' => true,
      'sort' => true,
      'args' => array( 'orderby' => 'term_order' ),
      'show_admin_column' => true
      )
    );
}
add_action( 'init', 'services_register_taxonomy' );

