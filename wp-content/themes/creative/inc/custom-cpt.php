<?php
// Register Testimonial post type and location taxonomy
function creative_register_testimonials_cpt() {
    // Register Custom Post Type: Testimonials
    register_post_type('testimonials', array(
        'labels' => array(
            'name' => 'Testimonials',
            'singular_name' => 'Testimonial',
            'add_new' => 'Add New Testimonial',
            'add_new_item' => 'Add New Testimonial',
            'edit_item' => 'Edit Testimonial',
            'new_item' => 'New Testimonial',
            'view_item' => 'View Testimonial',
            'all_items' => 'All Testimonials',
            'search_items' => 'Search Testimonials',
        ),
        'public' => true,
        'publicly_queryable' => false, // Disable single post viewing
        'supports' => array('title', 'editor', 'thumbnail'),
        'menu_icon' => 'dashicons-testimonial',
        'rewrite' => array('slug' => 'testimonials'),
    ));

    // Register Taxonomy: Location
    register_taxonomy('location', 'testimonials', array(
        'labels' => array(
            'name' => 'Locations',
            'singular_name' => 'Location',
            'search_items' => 'Search Locations',
            'all_items' => 'All Locations',
            'edit_item' => 'Edit Location',
            'update_item' => 'Update Location',
            'add_new_item' => 'Add New Location',
            'new_item_name' => 'New Location Name',
            'menu_name' => 'Locations',
        ),
        'hierarchical' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'rewrite' => array('slug' => 'location'),
    ));
}
add_action('init', 'creative_register_testimonials_cpt');
